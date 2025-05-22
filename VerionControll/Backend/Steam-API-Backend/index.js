const express = require('express');
const cors = require('cors');
const fetch = require('node-fetch');

const app = express();
const port = 3622;


const STEAM_API_KEY = 'DEIN_STEAM_API_KEY_HIER';
const STEAM_ID = '76561199187129606';

app.use(cors({
    origin: '*',
    methods: ['GET', 'POST', 'PUT', 'DELETE'],
    allowedHeaders: ['Content-Type', 'Authorization']
}));

app.use(express.json());

app.get('/steam-status', async (req, res) => {
    try {
        const response = await fetch(
            `https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=${STEAM_API_KEY}&steamids=${STEAM_ID}`
        );

        const data = await response.json();
        const player = data.response.players[0];

        const result = {
            personaState: player.personastate,
            status: getStatusText(player.personastate),
            lastLogOff: player.lastlogoff ? formatLastSeen(player.lastlogoff * 1000) : null
        };

        if (player.gameextrainfo && player.gameid) {
            result.currentGame = {
                name: player.gameextrainfo,
                id: player.gameid
            };
        }

        res.json(result);

    } catch (error) {
        console.error('Steam API error:', error);
        res.status(500).json({ error: 'Failed to fetch Steam data' });
    }
});

function getStatusText(state) {
    const states = {
        0: 'Offline', 1: 'Online', 2: 'Busy', 3: 'Away',
        4: 'Snooze', 5: 'Looking to trade', 6: 'Looking to play'
    };
    return states[state] || 'Unknown';
}

function formatLastSeen(timestamp) {
    const now = Date.now();
    const diff = now - timestamp;
    const minutes = Math.floor(diff / (1000 * 60));
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (minutes < 1) return 'Just now';
    if (minutes < 60) return `${minutes}m ago`;
    if (hours < 24) return `${hours}h ago`;
    if (days < 7) return `${days}d ago`;

    return new Date(timestamp).toLocaleDateString();
}

app.listen(port, () => {
    console.log(`Steam API running on port ${port}`);
});