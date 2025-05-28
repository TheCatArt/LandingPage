# Cat's Dashboard 🐱

A modern, responsive personal dashboard featuring real-time integrations with various services and platforms.

![Dashboard Preview](https://img.shields.io/badge/Status-Live-brightgreen) ![Version](https://img.shields.io/badge/Version-1.16-blue) ![License](https://img.shields.io/badge/License-MIT-yellow)

## ✨ Features

### 🎵 Music Integration
- **Real-time Now Playing** - Live Last.fm integration showing currently playing tracks
- **Recent Tracks History** - Display of recently played music with timestamps
- **Animated Music Visualizer** - Visual feedback when music is playing

### 🌤️ Weather & Time
- **Live Weather Data** - Current conditions for Klagenfurt, Austria
- **Animated Weather Icons** - Dynamic icons that respond to weather conditions
- **Digital Clock** - Smooth animated time display
- **Interactive Calendar** - Monthly view with navigation

### 🖥️ Server Monitoring
- **Real-time Server Stats** - CPU, RAM, and storage usage
- **Service Status** - Monitor hosted services and their availability
- **Subserver Overview** - Status tracking for multiple server instances

### 🎮 Gaming Profiles
- **Steam Integration** - Live gaming status and currently playing games
- **Discord Widget** - Embedded Discord profile with rich presence
- **VR Game Detection** - Special indicators for VR gaming sessions

### 🔗 Social Media Hub
- **Platform Links** - Quick access to all social media profiles
- **Custom Hover Effects** - Platform-specific styling and animations
- **Friend Network** - Links to friends' websites and projects

### 📱 Responsive Design
- **Mobile-First** - Fully responsive grid layout
- **Dark Theme** - Modern dark UI with gradient accents
- **Smooth Animations** - Fluid transitions and hover effects
- **Glassmorphism** - Modern blur effects and transparency

## 🛠️ Tech Stack

- **Frontend**: Vanilla HTML5, CSS3, JavaScript (ES6+)
- **APIs**: Last.fm, wttr.in Weather, Steam Web API
- **Styling**: CSS Grid, Flexbox, Custom Properties
- **Icons**: Font Awesome 6.4.0
- **Fonts**: Segoe UI system font stack

## 🚀 Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/TheCatArt/dashboard.git
   cd dashboard
   ```

2. **Configure API Keys**
    - Replace the Last.fm API key in the JavaScript section
    - Set up your Last.fm username
    - Configure Steam ID for gaming integration

3. **Customize Content**
    - Update personal information in the "About Me" section
    - Modify social media links
    - Adjust server information and endpoints

4. **Deploy**
    - Upload to your web server
    - Ensure CORS is properly configured for API requests
    - Set up backend endpoints for server stats (optional)

## ⚙️ Configuration

### API Configuration
```javascript
// Last.fm Integration
const lastfmUsername = 'Your_Username';
const lastfmApiKey = 'your_api_key_here';

// Steam Integration
const steamId = 'your_steam_id';
```

### Weather Location
The weather service automatically tries multiple location formats for Klagenfurt, Austria. To change location:
```javascript
const locationNames = [
    'Your_City,Country',
    // Add alternative formats
];
```

### Server Monitoring
Configure your server endpoints:
```javascript
// Server stats endpoint
const response = await fetch('/stats');

// Steam status endpoint  
const response = await fetch('/steam-status');
```

## 🎨 Customization

### Color Scheme
The dashboard uses CSS custom properties for easy theming:
```css
:root {
    --bg-primary: #0f0f1a;
    --bg-secondary: #16162a;
    --accent-blue: #2a6af3;
    --accent-purple: #8a2be2;
    --text-primary: #ffffff;
    --text-secondary: #b0b0cc;
}
```

### Adding New Cards
1. Create a new card in the HTML grid
2. Add corresponding styles
3. Implement functionality in JavaScript
4. Update navigation menu

## 📊 Backend Requirements (Optional)

For full functionality, set up these endpoints:

- **`/stats`** - Server statistics (CPU, RAM, Storage)
- **`/steam-status`** - Steam API proxy to avoid CORS issues

Example backend response format:
```json
{
    "cpu": "25.5",
    "ram": "68.2", 
    "storage": "45.8"
}
```

## 🌐 Live Demo

Visit the live dashboard: [thecatart.com](https://thecatart.com)

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


## 📞 Contact

- **Website**: [thecatart.com](https://thecatart.com)
- **Discord**: @the_cat_art
- **Instagram**: [@the_cat_art1](https://instagram.com/the_cat_art1)
- **GitHub**: [@TheCatArt](https://github.com/TheCatArt)

## 🙏 Acknowledgments

- [Last.fm API](https://www.last.fm/api) for music data
- [wttr.in](https://wttr.in) for weather information
- [Font Awesome](https://fontawesome.com) for icons
- [Vendicated Discord Widgets](https://widgets.vendicated.dev) for Discord integration

---

Made with 🧡 by [The_Cat_Art](https://github.com/TheCatArt)