function updateClock() {
    const now = new Date();
    const timeString = [
        ...String(now.getHours()).padStart(2, '0'),
        ':',
        ...String(now.getMinutes()).padStart(2, '0'),
        ':',
        ...String(now.getSeconds()).padStart(2, '0')
    ];

    const containers = document.querySelectorAll('#clock .digit-container');

    containers.forEach((container, i) => {
        const current = container.querySelector('.digit:not(.digit-exit)');
        const newChar = timeString[i];

        if (current && current.textContent === newChar) return;

        if (current) {
            current.classList.add('digit-exit');
            current.addEventListener('animationend', () => {
                current.remove();
            }, { once: true });
        }

        const newDigit = document.createElement('span');
        newDigit.className = 'digit digit-enter';
        newDigit.textContent = newChar;
        container.appendChild(newDigit);
    });

    const dateElement = document.getElementById('date');
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    dateElement.textContent = now.toLocaleDateString('en-US', options);
}



setInterval(updateClock, 1000);
updateClock();



function updateDigits(containerId, newValue) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const oldDigits = container.querySelectorAll('.digit-container');
    oldDigits.forEach(d => {
        d.firstChild.classList.add('digit-exit');
        setTimeout(() => d.remove(), 500);
    });

    for (let char of newValue) {
        const wrapper = document.createElement('span');
        wrapper.className = 'digit-container';

        const digit = document.createElement('span');
        digit.className = 'digit digit-enter';
        digit.textContent = char;

        wrapper.appendChild(digit);
        container.appendChild(wrapper);
    }
}



setInterval(updateClock, 1000);
updateClock();


const lastfmUsername = 'The_Cat_Art';
const lastfmApiKey = '9b3e37481d2acb937ec14df3653b7e61';
const nowPlayingContainer = document.getElementById('now-playing-container');
const nowPlayingPlaceholder = document.getElementById('now-playing-placeholder');
const recentTracks = document.getElementById('recent-tracks');


async function fetchLastFmData() {
    try {
        const response = await fetch(`https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=${lastfmUsername}&api_key=${lastfmApiKey}&format=json&limit=7`);
        const data = await response.json();

        if (!data.recenttracks || !data.recenttracks.track || data.recenttracks.track.length === 0) {
            throw new Error('No recent tracks found');
        }

        const tracks = data.recenttracks.track;
        const isPlaying = tracks[0]['@attr'] && tracks[0]['@attr'].nowplaying === 'true';


        if (isPlaying) {
            const currentTrack = tracks[0];
            nowPlayingPlaceholder.style.display = 'none';

            nowPlayingContainer.innerHTML = `
                <img src="${currentTrack.image[2]['#text'] || 'https://via.placeholder.com/60'}" alt="Album art" class="album-art">
                <div class="track-info">
                    <div class="track-title">${currentTrack.name}</div>
                    <div class="track-artist">${currentTrack.artist['#text']}</div>
                </div>
                <div class="music-visualizer">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            `;
        } else {
            nowPlayingPlaceholder.style.display = 'block';
            const visualizer = document.querySelector('.music-visualizer');
            if (visualizer) {
                visualizer.style.display = 'none';
            }
        }


        recentTracks.innerHTML = '';


        const startIndex = isPlaying ? 1 : 0;

        for (let i = startIndex; i < tracks.length; i++) {
            const track = tracks[i];
            const timeAgo = track.date ? new Date(track.date.uts * 1000) : null;
            let timeAgoText = '';
            let exactDateTime = '';

            if (timeAgo) {
                const now = new Date();
                const diffMinutes = Math.floor((now - timeAgo) / (1000 * 60));

                if (diffMinutes < 60) {
                    timeAgoText = `${diffMinutes}m ago`;
                } else {
                    const diffHours = Math.floor(diffMinutes / 60);
                    if (diffHours < 24) {
                        timeAgoText = `${diffHours}h ago`;
                    } else {
                        const diffDays = Math.floor(diffHours / 24);
                        timeAgoText = `${diffDays}d ago`;
                    }
                }


                const dateOptions = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                exactDateTime = timeAgo.toLocaleDateString('en-US', dateOptions);
            }

            recentTracks.innerHTML += `
                <li class="history-item">
                    <img src="${track.image[1]['#text'] || 'https://via.placeholder.com/40'}" alt="Album art" class="history-album">
                    <div class="history-info">
                        <div class="history-title">${track.name}</div>
                        <div class="history-artist">${track.artist['#text']}</div>
                    </div>
                    <div class="history-time">
                        <div>${timeAgoText}</div>
                        <div style="font-size: 0.7rem; margin-top: 0.2rem;">${exactDateTime}</div>
                    </div>
                </li>
            `;
        }
    } catch (error) {
        console.error('Error fetching Last.fm data:', error);
        nowPlayingContainer.innerHTML = `
            <div id="now-playing-placeholder">
                <p>Could not load music data...</p>
            </div>
        `;
        recentTracks.innerHTML = `
            <li class="history-item">
                <div style="width: 40px; height: 40px; border-radius: 6px; background: rgba(40, 40, 60, 0.5); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-circle" style="color: var(--accent-purple);"></i>
                </div>
                <div class="history-info">
                    <div class="history-title">Failed to load recent tracks</div>
                    <div class="history-artist">Check your Last.fm connection</div>
                </div>
            </li>
        `;
    }

}


fetchLastFmData();
setInterval(fetchLastFmData, 20000);


async function fetchWeatherData() {
    const weatherContainer = document.getElementById('weather-container');


    const locationNames = [
        'Klagenfurt,Austria',
        'Klagenfurt+am+Woerthersee',
        'Klagenfurt+am+Wörthersee',
        'Klagenfurt,AT',
        'Klagenfurt,Carinthia',
        'Villach,Austria',
        'Graz,Austria'
    ];

    for (const location of locationNames) {
        try {
            console.log(`🌍 Trying location: ${location}`);


            let response = await fetch(`https://wttr.in/${location}?format=j1`, {
                method: 'GET',
                headers: {
                    'User-Agent': 'curl/7.68.0'
                }
            });

            if (!response.ok) {

                response = await fetch('https://corsproxy.io/?' + encodeURIComponent(`https://wttr.in/${location}?format=j1`));
            }

            if (!response.ok) {
                console.log(`❌ ${location} failed with status: ${response.status}`);
                continue;
            }

            const text = await response.text();
            console.log(`📍 Response for ${location}:`, text.substring(0, 100));


            if (text.includes('Unknown location') || text.includes('ERROR')) {
                console.log(`❌ ${location} returned error`);
                continue;
            }


            let data;
            try {
                data = JSON.parse(text);
            } catch (parseError) {

                const cleanedText = text.trim();
                const jsonStart = cleanedText.indexOf('{');
                const jsonEnd = cleanedText.lastIndexOf('}') + 1;

                if (jsonStart !== -1 && jsonEnd > jsonStart) {
                    const jsonOnly = cleanedText.substring(jsonStart, jsonEnd);
                    data = JSON.parse(jsonOnly);
                } else {
                    console.log(`❌ Could not parse JSON for ${location}`);
                    continue;
                }
            }


            if (!data || !data.current_condition || !data.current_condition[0]) {
                console.log(`❌ Invalid data structure for ${location}`);
                continue;
            }


            console.log(`✅ SUCCESS with location: ${location}`);

            const current = data.current_condition[0];
            const hourlyData = data.weather?.[0]?.hourly || [];


            const now = new Date();
            const currentTime = now.getHours() * 100;
            const latestHourData = hourlyData
                .filter(h => parseInt(h.time) <= currentTime)
                .pop() || hourlyData[0] || current;


            const temp = Math.round(parseFloat(latestHourData.tempC || current.temp_C) || 0);
            const condition = (latestHourData.weatherDesc?.[0]?.value || current.weatherDesc?.[0]?.value || 'Unknown').toLowerCase();

            let windSpeed = parseFloat(latestHourData.windspeedKmph || current.windspeedKmh || current.WindSpeedKmph) || 0;
            windSpeed = Math.round(windSpeed);

            let windDir = latestHourData.winddir16Point || current.winddir16Point || current.WindDir16Point || 'N';
            const humidity = parseInt(latestHourData.humidity || current.humidity) || 0;


            let weatherIcon = 'fa-cloud';
            let iconColor = '#87CEEB';
            let animationClass = '';

            if (condition.includes('clear') || condition.includes('sunny')) {
                weatherIcon = 'fa-sun';
                iconColor = '#FFD700';
                animationClass = 'sun-animation';
            } else if (condition.includes('rain') || condition.includes('drizzle') || condition.includes('shower')) {
                weatherIcon = 'fa-cloud-rain';
                iconColor = '#4682B4';
                animationClass = 'rain-animation';
            } else if (condition.includes('snow')) {
                weatherIcon = 'fa-snowflake';
                iconColor = '#B0E0E6';
                animationClass = 'snow-animation';
            } else if (condition.includes('thunder') || condition.includes('storm')) {
                weatherIcon = 'fa-bolt';
                iconColor = '#FFD700';
                animationClass = 'thunder-animation';
            } else if (condition.includes('mist') || condition.includes('fog') || condition.includes('haze')) {
                weatherIcon = 'fa-smog';
                iconColor = '#D3D3D3';
                animationClass = 'fog-animation';
            } else if (condition.includes('cloud') || condition.includes('overcast')) {
                weatherIcon = 'fa-cloud';
                iconColor = '#B0C4DE';
                animationClass = 'cloud-animation';
            } else if (condition.includes('partly')) {
                weatherIcon = 'fa-cloud-sun';
                iconColor = '#FFD700';
                animationClass = 'sun-animation';
            }


            const windDirections = {
                'N': 0, 'NNE': 22.5, 'NE': 45, 'ENE': 67.5,
                'E': 90, 'ESE': 112.5, 'SE': 135, 'SSE': 157.5,
                'S': 180, 'SSW': 202.5, 'SW': 225, 'WSW': 247.5,
                'W': 270, 'WNW': 292.5, 'NW': 315, 'NNW': 337.5
            };
            const windRotation = windDirections[windDir] || 0;

            console.log(`Weather data from ${location}: ${temp}°C, ${condition}, Wind: ${windSpeed} km/h ${windDir}`);


            const locationDisplay = location.includes('Villach') ? 'Villach (near Klagenfurt)' :
                location.includes('Graz') ? 'Graz, Austria' :
                    'Klagenfurt am Wörthersee';


            weatherContainer.innerHTML = `
                <div class="weather-icon">
                    <i class="fas ${weatherIcon} ${animationClass}" style="color: ${iconColor};"></i>
                </div>
                <div class="weather-details">
                    <div class="temp">${temp}°C</div>
                    <div class="condition">${latestHourData.weatherDesc?.[0]?.value || current.weatherDesc?.[0]?.value || 'Unknown'}</div>
                    <div class="weather-meta">
                        <div class="weather-meta-item">
                            <i class="fas fa-location-arrow wind-arrow" style="transform: rotate(${windRotation}deg); color: var(--accent-blue);"></i>
                            <span>${windSpeed} km/h ${windDir}</span>
                        </div>
                        <div class="weather-meta-item">
                            <i class="fas fa-tint" style="color: #4682B4;"></i>
                            <span>${humidity}%</span>
                        </div>
                    </div>
                </div>
            `;


            if (!location.toLowerCase().includes('klagenfurt')) {
                const locationNameEl = document.querySelector('.location-name span');
                if (locationNameEl) {
                    locationNameEl.textContent = locationDisplay;
                }
            }

            return;

        } catch (error) {
            console.log(`❌ Error with ${location}:`, error.message);
            continue;
        }
    }


    console.error('🚨 All weather locations failed!');
    weatherContainer.innerHTML = `
        <div class="weather-icon">
            <i class="fas fa-exclamation-circle" style="color: #FF6B6B;"></i>
        </div>
        <div class="weather-details">
            <div class="temp">N/A</div>
            <div class="condition">Weather service unavailable</div>
            <div class="weather-meta">
                <div class="weather-meta-item">
                    <i class="fas fa-wind"></i> <span>-- km/h</span>
                </div>
                <div class="weather-meta-item">
                    <i class="fas fa-tint"></i> <span>--%</span>
                </div>
            </div>
        </div>
    `;
}

fetchWeatherData();
setInterval(fetchWeatherData, 1800000);


function generateCalendar() {
    const calendarDays = document.getElementById('calendar-days');
    const calendarMonth = document.getElementById('calendar-month');


    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    function updateCalendar() {

        calendarDays.style.opacity = 0;

        setTimeout(() => {

            calendarDays.innerHTML = '';


            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            calendarMonth.textContent = `${monthNames[currentMonth]} ${currentYear}`;


            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();


            const prevMonthDays = new Date(currentYear, currentMonth, 0).getDate();


            for (let i = firstDay - 1; i >= 0; i--) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day other-month';
                dayEl.textContent = prevMonthDays - i;
                calendarDays.appendChild(dayEl);
            }


            for (let i = 1; i <= daysInMonth; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day';


                if (currentMonth === today.getMonth() && currentYear === today.getFullYear() && i === today.getDate()) {
                    dayEl.classList.add('today');
                }

                dayEl.textContent = i;
                calendarDays.appendChild(dayEl);
            }


            const totalCells = 42;
            const remainingCells = totalCells - (firstDay + daysInMonth);

            for (let i = 1; i <= remainingCells; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day other-month';
                dayEl.textContent = i;
                calendarDays.appendChild(dayEl);
            }


            setTimeout(() => {
                calendarDays.style.opacity = 1;
            }, 50);
        }, 300);
    }


    updateCalendar();


    document.getElementById('prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendar();
    });
}


generateCalendar();


async function fetchServerStats() {
    try {
        const response = await fetch('/stats', {
            method: 'GET',
            mode: 'cors',
            headers: {
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Server stats received:', data); // Debug log

        const statValues = document.querySelectorAll('.stat-value');

        statValues[0].textContent = `${parseFloat(data.cpu).toFixed(1)}%`;
        statValues[1].textContent = `${Math.round(parseFloat(data.ram))}%`;
        statValues[2].textContent = `${Math.round(parseFloat(data.storage))}%`;


    } catch (error) {
        console.error('Error fetching server stats:', error);
        const statValues = document.querySelectorAll('.stat-value');
        statValues[0].textContent = 'N/A';
        statValues[1].textContent = 'N/A';
        statValues[2].textContent = 'N/A';
    }
}


fetchServerStats();
setInterval(fetchServerStats, 5000);


const subservers = [
    { name: 'Node', ip: '', status: 'online'},
    { name: 'Server', ip: '45.147.7.220:20000', status: 'online'},

];

function updateSubserverStatus() {
    const subserverList = document.getElementById('subserver-list');
    subserverList.innerHTML = '';

    subservers.forEach(server => {
        const listItem = document.createElement('li');
        listItem.className = 'subserver-item';

        listItem.innerHTML = `
                <div class="subserver-name">
                    <div class="subserver-indicator ${server.status}"></div>
                    <span>${server.name}</span>
                </div>
                <span>${server.ip}</span>
            `;

        subserverList.appendChild(listItem);
    });
}

updateSubserverStatus();

const steamId = '76561199187129606';

async function fetchSteamStatus() {
    const steamContainer = document.getElementById('steam-currently-playing');

    try {

        const response = await fetch('/steam-status', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`Backend request failed: ${response.status}`);
        }

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        if (data.currentGame && data.currentGame.name) {

            const vrGames = ['Half-Life: Alyx', 'Beat Saber', 'VRChat', 'Pavlov VR', 'Boneworks', 'The Lab', 'Superhot VR', 'Job Simulator', 'Blade & Sorcery', 'Arizona Sunshine', 'Onward', 'Rec Room', 'Elite Dangerous', 'DCS World', 'No Man\'s Sky', 'Skyrim VR', 'Fallout 4 VR'];
            const isVRGame = vrGames.some(vrGame => data.currentGame.name.toLowerCase().includes(vrGame.toLowerCase()));


            const gameImageUrl = `https://steamcdn-a.akamaihd.net/steam/apps/${data.currentGame.id}/header.jpg`;

            steamContainer.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: rgba(23, 26, 33, 0.8); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        <img src="${gameImageUrl}"
                             alt="${data.currentGame.name}"
                             style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; position: absolute; top: 0; left: 0; background: rgba(23, 26, 33, 0.8);">
                            <i class="fab fa-steam" style="color: #66c0f4; font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                            ${data.currentGame.name}
                            ${isVRGame ? '<i class="fas fa-vr-cardboard" style="color: #ff6b6b; font-size: 1rem;" title="VR Game"></i>' : ''}
                        </div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">
                            Currently playing on Steam${isVRGame ? ' (VR)' : ''}
                        </div>
                    </div>
                    <div style="width: 12px; height: 12px; background: #4caf50; border-radius: 50%; box-shadow: 0 0 8px #4caf50; animation: pulse 2s infinite;"></div>
                </div>
            `;
        } else {

            let statusText = data.status || 'Online';
            let statusColor = '#57cbde';

            switch(data.personaState) {
                case 0: statusText = 'Offline'; statusColor = '#898989'; break;
                case 1: statusText = 'Online'; statusColor = '#2196f3'; break;
                case 2: statusText = 'Busy'; statusColor = '#d32f2f'; break;
                case 3: statusText = 'Away'; statusColor = '#ff9800'; break;
                case 4: statusText = 'Snooze'; statusColor = '#ff9800'; break;
                case 5: statusText = 'Looking to trade'; statusColor = '#2196f3'; break;
                case 6: statusText = 'Looking to play'; statusColor = '#8bc34a'; break;
            }

            steamContainer.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: rgba(23, 26, 33, 0.8); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fab fa-steam" style="color: ${statusColor}; font-size: 1.5rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">
                            Not playing anything
                        </div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">
                            Status: ${statusText}
                        </div>
                        ${data.lastLogOff ? `<div style="font-size: 0.8rem; color: var(--text-secondary); opacity: 0.8;">Last seen: ${data.lastLogOff}</div>` : ''}
                    </div>
                    <div style="width: 12px; height: 12px; background: ${statusColor}; border-radius: 50%; box-shadow: 0 0 8px ${statusColor};"></div>
                </div>
            `;
        }

    } catch (error) {
        console.error('Error fetching Steam data:', error);
        steamContainer.innerHTML = `
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(40, 40, 60, 0.5); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-circle" style="color: #ff6b6b; font-size: 1.2rem;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 1rem; color: var(--text-secondary);">
                        Steam status unavailable
                    </div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); opacity: 0.7;">
                        Backend service error
                    </div>
                </div>
            </div>
        `;
    }
}


fetchSteamStatus();
setInterval(fetchSteamStatus, 30000);


document.getElementById('secret-trigger').addEventListener('click', function() {
    this.style.transform = 'scale(0.95)';
    setTimeout(() => {
        this.style.transform = 'scale(1)';
    }, 100);

    setTimeout(() => {
        window.location.href = '/easter-egg.html';
    }, 200);
});