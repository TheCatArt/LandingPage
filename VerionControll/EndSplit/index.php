<?php
$ip = $_SERVER['REMOTE_ADDR'];
$info = json_decode(file_get_contents("http://ip-api.com/json/$ip"), true);
$country = $info['country'];
file_get_contents("WEBHOOK_LINK", false, stream_context_create([
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/json",
        "content" => json_encode(["content" => "$ip -> $country"])
    ]
]));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cat's Dashboard</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #0f0f1a;
            --bg-secondary: #16162a;
            --accent-blue: #2a6af3;
            --accent-purple: #8a2be2;
            --text-primary: #ffffff;
            --text-secondary: #b0b0cc;
            --card-bg: rgba(30, 30, 50, 0.7);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(138, 43, 226, 0.3);
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--accent-blue), var(--accent-purple));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 15px rgba(138, 43, 226, 0.3);
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }

        nav a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem;
        }

        nav a:hover {
            color: var(--text-primary);
        }

        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
            transition: width 0.3s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.3);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card h2 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card h2 i {
            color: var(--accent-purple);
            font-size: 1.2rem;
        }

        .card p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        @keyframes gradient-text {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .logo {
            background-size: 200% auto;
            animation: gradient-text 3s linear infinite;
        }

        .now-playing {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .album-art {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .track-info {
            flex: 1;
        }

        .track-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .track-artist {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .music-visualizer {
            display: flex;
            align-items: flex-end;
            gap: 3px;
            height: 30px;
        }

        .bar {
            width: 3px;
            background: linear-gradient(to top, var(--accent-blue), var(--accent-purple));
            border-radius: 3px;
            animation: visualize 0.5s infinite alternate;
        }

        @keyframes visualize {
            0% {
                height: 5px;
            }
            100% {
                height: 25px;
            }
        }

        .bar:nth-child(1) { animation-delay: 0.1s; }
        .bar:nth-child(2) { animation-delay: 0.2s; }
        .bar:nth-child(3) { animation-delay: 0.3s; }
        .bar:nth-child(4) { animation-delay: 0.2s; }
        .bar:nth-child(5) { animation-delay: 0.1s; }

        .history-list {
            list-style: none;
            margin-top: 1rem;
        }

        .history-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-album {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
        }

        .history-info {
            flex: 1;
        }

        .history-title {
            font-size: 0.9rem;
            color: var(--text-primary);
            margin-bottom: 0.15rem;
        }

        .history-artist {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .history-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .weather-info {
            display: flex;
            align-items: center;
            margin-top: 1rem;
        }

        .weather-icon {
            font-size: 2.5rem;
            margin-right: 1rem;
            color: var(--accent-blue);
        }

        .weather-details {
            flex: 1;
        }

        .temp {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .condition {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .weather-meta {
            display: flex;
            margin-top: 0.75rem;
            gap: 1.5rem;
        }

        .weather-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .service-item {
            background: rgba(40, 40, 60, 0.5);
            border-radius: 10px;
            padding: 1.25rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .service-item:hover {
            background: rgba(60, 60, 80, 0.5);
            transform: translateY(-5px);
        }

        .service-icon {
            font-size: 1.75rem;
            margin-bottom: 0.75rem;
            color: var(--accent-purple);
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .service-item:hover .service-icon {
            transform: scale(1.1);
            color: var(--accent-blue);
        }

        .service-name {
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .service-soon {
            font-size: 0.7rem;
            color: var(--accent-purple);
            margin-top: 0.3rem;
        }

        .info-content {
            margin-top: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(42, 106, 243, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--accent-blue);
        }

        .info-text {
            flex: 1;
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-primary);
        }

        .social-links {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-primary);
            background: rgba(40, 40, 60, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(60, 60, 80, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .friends-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .friend-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-primary);
            background: rgba(40, 40, 60, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .friend-link:hover {
            background: rgba(60, 60, 80, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .footer {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(138, 43, 226, 0.3);
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer p {
            margin-bottom: 1rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--accent-purple);
        }

        .server-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #4CAF50;
            box-shadow: 0 0 10px #4CAF50;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(76, 175, 80, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
            }
        }

        .server-stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .stat-item {
            flex: 1;
            padding: 0.75rem;
            background: rgba(40, 40, 60, 0.5);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .glow {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 600px;
            height: 600px;
            margin-left: -300px;
            margin-top: -300px;
            background: radial-gradient(circle, rgba(138, 43, 226, 0.15) 0%, rgba(42, 106, 243, 0.15) 25%, rgba(0, 0, 0, 0) 70%);
            z-index: -1;
            animation: rotate 60s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .clock {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: center;
            gap: 0.2rem;
        }



        .date {
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .calendar {
            margin-top: 2rem;
            background: rgba(30, 30, 50, 0.5);
            border-radius: 10px;
            padding: 1rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }



        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .calendar-nav-btn {
            background: rgba(60, 60, 80, 0.5);
            border: none;
            color: var(--text-primary);
            width: 30px;
            height: 30px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .calendar-nav-btn:hover {
            background: var(--accent-purple);
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 0.5rem;
        }

        .calendar-weekday {
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-secondary);
            padding: 0.5rem 0;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }


        .digit-container {
            width: 1ch;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }


        .digit {
            position: absolute;
            left: 0;
            animation-duration: 0.5s;
            animation-timing-function: ease-in-out;
            animation-fill-mode: forwards;
        }

        .digit-enter {
            animation-name: digitSlideIn;
        }


        @keyframes digitSlideIn {
            from {
                top: -2rem;
                opacity: 0;
            }
            to {
                top: 0;
                opacity: 1;
            }
        }

        @keyframes digitSlideOut {
            from {
                top: 0;
                opacity: 1;
            }
            to {
                top: 2rem;
                opacity: 0;
            }
        }


        .calendar-month {
            position: relative;
            height: 1.5rem;
            overflow: hidden;
        }

        .month-text {
            position: absolute;
            left: 0;
            width: 100%;
            text-align: left;
        }

        .month-enter-next {
            animation-name: monthSlideInNext;
        }

        .month-exit-next {
            animation-name: monthSlideOutNext;
        }

        .month-enter-prev {
            animation-name: monthSlideInPrev;
        }

        .month-exit-prev {
            animation-name: monthSlideOutPrev;
        }

        @keyframes monthSlideInNext {
            from {
                top: -2rem;
                opacity: 0;
            }
            to {
                top: 0;
                opacity: 1;
            }
        }

        @keyframes monthSlideOutNext {
            from {
                top: 0;
                opacity: 1;
            }
            to {
                top: 2rem;
                opacity: 0;
            }
        }

        @keyframes monthSlideInPrev {
            from {
                top: 2rem;
                opacity: 0;
            }
            to {
                top: 0;
                opacity: 1;
            }
        }

        @keyframes monthSlideOutPrev {
            from {
                top: 0;
                opacity: 1;
            }
            to {
                top: -2rem;
                opacity: 0;
            }
        }

        .clock {
            display: flex;
            justify-content: center;
            gap: 0.3rem;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .digit-container {
            width: 1ch;
            height: 2.4rem;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .digit {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            line-height: 2.4rem;
        }

        .digit-enter {
            animation: digitSlideIn 0.5s ease-in-out forwards;
        }

        .digit-exit {
            animation: digitSlideOut 0.5s ease-in-out forwards;
        }

        @keyframes digitSlideIn {
            from {
                top: -2.4rem;
                opacity: 0;
            }
            to {
                top: 0;
                opacity: 1;
            }
        }

        @keyframes digitSlideOut {
            from {
                top: 0;
                opacity: 1;
            }
            to {
                top: 2.4rem;
                opacity: 0;
            }
        }



        .social-link {
            transition: all 0.3s ease, background-color 0.3s ease;
        }


        .social-link.instagram:hover {
            background: linear-gradient(45deg, #FCAF45, #E1306C, #C13584, #833AB4, #5851DB, #405DE6);
            border-color: transparent;
        }


        .social-link.tiktok:hover {
            background: linear-gradient(45deg, #25F4EE, #000000, #FE2C55);
            border-color: transparent;
        }


        .social-link.discord:hover {
            background-color: #7289DA;
            border-color: #7289DA;
        }


        .social-link.github:hover {
            background-color: #333333;
            border-color: #333333;
        }


        .social-link.reddit:hover {
            background-color: #FF4500;
            border-color: #FF4500;
        }


        .social-link.spotify:hover {
            background-color: #1DB954;
            border-color: #1DB954;
        }


        .social-link.twitter:hover {
            background-color: #1DA1F2;
            border-color: #1DA1F2;
        }


        .social-link.steam:hover {
            background-color: #171a21;
            border-color: #171a21;
        }


        .social-link.youtube:hover {
            background-color: #FF0000;
            border-color: #FF0000;
        }


        .social-link.twitch:hover {
            background-color: #9146FF;
            border-color: #9146FF;
        }


        .social-link.lastfm:hover {
            background-color: #d51007;
            border-color: #d51007;
        }


        .social-link.tidal:hover {
            background-color: #000000;
            border-color: #00FFFF;
        }


        .social-link.pinterest:hover {
            background-color: #E60023;
            border-color: #E60023;
        }


        .social-link.bluesky:hover {
            background-color: #0085FF;
            border-color: #0085FF;
        }


        .fa-tidal {
            position: relative;
            width: 1em;
            height: 1em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .fa-tidal:before {
            content: "";
            position: absolute;
            width: 0.8em;
            height: 0.8em;
            background: currentColor;
            clip-path: polygon(0 100%, 33% 0, 66% 100%, 100% 0);
        }

        .calendar-day {
            text-align: center;
            padding: 0.5rem 0;
            border-radius: 5px;
            font-size: 0.9rem;
            background: rgba(40, 40, 60, 0.3);
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .calendar-day:hover {
            background: rgba(60, 60, 80, 0.5);
        }

        .calendar-day.today {
            background: var(--accent-blue);
            color: white;
            font-weight: 600;
        }

        .calendar-day.other-month {
            color: rgba(176, 176, 204, 0.4);
        }



        .social-link:hover i {
            text-shadow:
                    -1px -1px 0 #000,
                    1px -1px 0 #000,
                    -1px 1px 0 #000,
                    1px 1px 0 #000,
                    0 0 3px rgba(0,0,0,0.8);
            filter: brightness(1.2) contrast(1.1);
        }


        .social-link.spotify:hover i,
        .social-link.github:hover i,
        .social-link.steam:hover i {
            color: #ffffff !important;
        }


        .social-link.tidal:hover i {
            color: #ffffff !important;
            text-shadow:
                    -1px -1px 0 #00FFFF,
                    1px -1px 0 #00FFFF,
                    -1px 1px 0 #00FFFF,
                    1px 1px 0 #00FFFF,
                    0 0 5px #00FFFF;
        }


        .social-link.youtube:hover i {
            color: #ffffff !important;
        }


        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            transition: opacity 0.3s ease;
        }

        .location-name {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .location-icon {
            color: var(--accent-blue);
        }

        .subserver-list {
            list-style: none;
            margin-top: 1.5rem;
        }

        .subserver-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: rgba(40, 40, 60, 0.5);
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .subserver-name {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .subserver-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .online {
            background-color: #4CAF50;
            box-shadow: 0 0 5px #4CAF50;
        }

        .offline {
            background-color: #F44336;
            box-shadow: 0 0 5px #F44336;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tabs {
            display: flex;
            margin: 1rem 0;
            border-bottom: 1px solid rgba(138, 43, 226, 0.3);
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .tab-button:hover {
            color: var(--text-primary);
        }

        .tab-button.active {
            color: var(--text-primary);
        }

        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-blue), var(--accent-purple));
        }


        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }

            header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            nav ul {
                flex-wrap: wrap;
            }

            .weather-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .server-stats {
                flex-direction: column;
            }

            .social-links {
                grid-template-columns: repeat(2, 1fr);
            }
        }


        .sun-animation {
            animation: sun-rotate 12s linear infinite;
        }


        .snow-animation {
            animation: snow-drift 4s ease-in-out infinite alternate;
        }

        .thunder-animation {
            animation: thunder-flash 3s ease-in-out infinite;
        }


        .cloud-animation {
            animation: cloud-drift 8s ease-in-out infinite alternate;
        }

        @keyframes cloud-drift {
            0% {
                transform: translateY(-1px);
                filter: drop-shadow(0 0 4px #B0C4DE);
            }
            100% {
                transform: translateY(1px);
                filter: drop-shadow(0 0 6px #B0C4DE);
            }
        }

        .wind-arrow {
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            animation: wind-pulse 3s ease-in-out infinite alternate;
        }

        @keyframes sun-rotate {
            from {
                transform: rotate(0deg);
                filter: brightness(1) drop-shadow(0 0 8px #FFD700);
            }
            to {
                transform: rotate(360deg);
                filter: brightness(1.1) drop-shadow(0 0 12px #FFD700);
            }
        }

        @keyframes rain-bounce {
            0% {
                transform: translateY(0px);
                filter: drop-shadow(0 0 4px #4682B4);
            }
            100% {
                transform: translateY(-2px);
                filter: drop-shadow(0 1px 6px #4682B4);
            }
        }

        @keyframes snow-drift {
            0% {
                transform: translateX(-1px) rotate(0deg);
                filter: drop-shadow(0 0 6px #B0E0E6);
            }
            100% {
                transform: translateX(1px) rotate(5deg);
                filter: drop-shadow(0 0 8px #B0E0E6);
            }
        }

        @keyframes thunder-flash {
            0%, 60%, 100% {
                opacity: 1;
                filter: drop-shadow(0 0 8px #FFD700);
            }
            30% {
                opacity: 0.8;
                filter: drop-shadow(0 0 15px #FFD700) brightness(1.3);
            }
        }

        @keyframes fog-float {
            0% {
                transform: translateX(-0.5px);
                opacity: 0.85;
                filter: blur(0.3px);
            }
            100% {
                transform: translateX(0.5px);
                opacity: 1;
                filter: blur(0px);
            }
        }

        @keyframes cloud-drift {
            0% {
                transform: translateX(-0.5px);
                filter: drop-shadow(0 0 4px #B0C4DE);
            }
            100% {
                transform: translateX(0.5px);
                filter: drop-shadow(0 0 6px #B0C4DE);
            }
        }

        @keyframes wind-pulse {
            0% {
                filter: drop-shadow(0 0 2px var(--accent-blue));
            }
            100% {
                filter: drop-shadow(0 0 6px var(--accent-blue)) brightness(1.1);
            }
        }

        .rain-animation {
            animation: rain-smooth 3s ease-in-out infinite alternate;
        }

        .fog-animation {
            animation: fog-wave 4s ease-in-out infinite;
            position: relative;
        }

        .fog-animation::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: -20%;
            width: 140%;
            height: 6px;
            background: linear-gradient(90deg, transparent, rgba(211, 211, 211, 0.6), transparent);
            border-radius: 50%;
            animation: fog-trail 3s ease-in-out infinite;
        }

        @keyframes rain-smooth {
            0% {
                transform: translateY(0px) scale(1);
                filter: drop-shadow(0 0 4px #4682B4);
            }
            50% {
                transform: translateY(-1px) scale(1.02);
                filter: drop-shadow(0 2px 6px #4682B4);
            }
            100% {
                transform: translateY(0px) scale(1);
                filter: drop-shadow(0 0 4px #4682B4);
            }
        }

        @keyframes fog-wave {
            0% {
                transform: translateX(0px);
                opacity: 0.85;
                filter: blur(0.5px);
            }
            25% {
                transform: translateX(1px);
                opacity: 0.9;
                filter: blur(0.3px);
            }
            50% {
                transform: translateX(0px);
                opacity: 1;
                filter: blur(0px);
            }
            75% {
                transform: translateX(-1px);
                opacity: 0.9;
                filter: blur(0.3px);
            }
            100% {
                transform: translateX(0px);
                opacity: 0.85;
                filter: blur(0.5px);
            }
        }

        @keyframes fog-trail {
            0% {
                transform: translateX(-10px);
                opacity: 0.3;
            }
            50% {
                transform: translateX(5px);
                opacity: 0.6;
            }
            100% {
                transform: translateX(-10px);
                opacity: 0.3;
            }
        }
        .secret-link {
            cursor: default; /* Normaler Cursor, nicht pointer */
            transition: all 0.3s ease;
            position: relative;
            color: inherit; /* Gleiche Farbe wie normaler Text */
        }

        .secret-link:hover {
            color: var(--accent-blue); /* Deine blaue Akzentfarbe */
            text-shadow: 0 0 10px rgba(42, 106, 243, 0.3);
            cursor: pointer; /* Zeigt Pointer nur beim Hover */
        }

        .secret-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent-blue);
            transition: width 0.3s ease;
        }

        .secret-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="glow"></div>
<div class="container">
    <header>
        <div class="logo">Welcome to my Dashboard!</div>
        <nav>
            <ul>
                <li><a href="#music">Music</a></li>
                <li><a href="#weather">Weather</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#info">Info</a></li>
                <li><a href="#social">Social</a></li>
                <li><a href="#friends">Friends</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#games">Games</a></li>
            </ul>
        </nav>
    </header>

    <main class="grid">
        <div class="card" id="music">
            <h2><i class="fas fa-music"></i> Now Playing</h2>
            <p>Track what I'm currently listening to and my recently played tracks.</p>
            <div class="now-playing" id="now-playing-container">
                <div id="now-playing-placeholder">
                    <p>Nothing playing right now...</p>
                </div>
            </div>

            <h3 style="margin-top: 2rem; margin-bottom: 0.5rem; font-size: 1.1rem;">Recent Tracks</h3>
            <ul class="history-list" id="recent-tracks">
                <li class="history-item">
                    <div style="width: 40px; height: 40px; border-radius: 6px; background: rgba(40, 40, 60, 0.5); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-music" style="color: var(--accent-purple);"></i>
                    </div>
                    <div class="history-info">
                        <div class="history-title">Loading tracks...</div>
                        <div class="history-artist"></div>
                    </div>
                </li>
            </ul>

        </div>

        <div class="card" id="weather">
            <h2><i class="fas fa-cloud"></i> Weather</h2>
            <p>Current weather conditions and forecast.</p>

            <div class="location-name">
                <i class="fas fa-map-marker-alt location-icon"></i>
                <span>Klagenfurt am Wörthersee</span>
            </div>

            <div class="weather-info" id="weather-container">
                <div class="weather-icon">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <div class="weather-details">
                    <div class="temp">Loading...</div>
                    <div class="condition"></div>

                    <div class="weather-meta">
                        <div class="weather-meta-item">
                            <i class="fas fa-wind"></i> <span id="wind-speed">--</span>
                        </div>
                        <div class="weather-meta-item">
                            <i class="fas fa-tint"></i> <span id="humidity">--</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <div class="clock" id="clock">
                    <span class="digit-container"><span class="digit">0</span></span>
                    <span class="digit-container"><span class="digit">0</span></span>
                    <span class="digit-container"><span class="digit">:</span></span>
                    <span class="digit-container"><span class="digit">0</span></span>
                    <span class="digit-container"><span class="digit">0</span></span>
                    <span class="digit-container"><span class="digit">:</span></span>
                    <span class="digit-container"><span class="digit">0</span></span>
                    <span class="digit-container"><span class="digit">0</span></span>
                </div>

                <div class="date" id="date">Wednesday, May 21, 2025</div>
            </div>

            <div class="calendar">
                <div class="calendar-header">
                    <div class="calendar-month" id="calendar-month">May 2025</div>
                    <div class="calendar-nav">
                        <button class="calendar-nav-btn" id="prev-month"><i class="fas fa-chevron-left"></i></button>
                        <button class="calendar-nav-btn" id="next-month"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="calendar-weekdays">
                    <div class="calendar-weekday">Sun</div>
                    <div class="calendar-weekday">Mon</div>
                    <div class="calendar-weekday">Tue</div>
                    <div class="calendar-weekday">Wed</div>
                    <div class="calendar-weekday">Thu</div>
                    <div class="calendar-weekday">Fri</div>
                    <div class="calendar-weekday">Sat</div>
                </div>
                <div class="calendar-days" id="calendar-days">

                </div>
            </div>
        </div>

        <div class="card" id="services">
            <h2><i class="fas fa-server"></i> Server Services</h2>
            <p>Access all hosted services on my server.</p>

            <div class="services-grid">
                <a href="https://panel.thecatart.com" target="_blank" class="service-item">
                    <i class="fas fa-th-large service-icon"></i>
                    <div class="service-name">Panel</div>
                </a>
                <a href="https://vault.thecatart.com" target="_blank" class="service-item">
                    <i class="fas fa-vault service-icon"></i>
                    <div class="service-name">Vault</div>
                </a>
                <a href="https://45.147.7.220:9443" target="_blank" class="service-item">
                    <i class="fas fa-ship service-icon"></i>
                    <div class="service-name">Portainer</div>
                </a>
                <div class="service-item">
                    <i class="fas fa-chart-line service-icon"></i>
                    <div class="service-name">Stats</div>
                    <div class="service-soon">Coming Soon</div>
                </div>
            </div>

            <div class="server-status" style="margin-top: 2rem;">
                <div class="status-indicator"></div>
                <span>Server active and running</span>
            </div>

            <div class="server-stats">
                <div class="stat-item">
                    <div class="stat-value">--</div>
                    <div class="stat-label">CPU</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">--</div>
                    <div class="stat-label">RAM</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">--</div>
                    <div class="stat-label">Storage</div>
                </div>
            </div>

            <h3 style="margin-top: 2rem; margin-bottom: 1rem; font-size: 1.1rem;">Subservers Status</h3>
            <ul class="subserver-list" id="subserver-list">
                <li class="subserver-item">
                    <div class="subserver-name">
                        <div class="subserver-indicator online"></div>
                        <span>Main Server</span>
                    </div>
                    <span>45.147.7.220</span>
                </li>
                <!-- More subservers can be added here -->
            </ul>
        </div>

        <div class="card" id="info">
            <h2><i class="fas fa-info-circle"></i> Server Info</h2>
            <p>Technical details and information about the server.</p>

            <div class="info-content">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Processor</div>
                        <div class="info-value">Intel(R) Xeon(R) CPU E5-2697 v4 @ 2.30GHz</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-memory"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Memory</div>
                        <div class="info-value">96 GB Synchronous DDR4 ECC 2400 MHz</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-hdd"></i>
                    </div>

                    <div class="info-text">
                        <div class="info-label">Storage</div>
                        <div class="info-value">300 GB Samsung SSD PM983</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Network</div>
                        <div class="info-value">Up to 2,000 Mbit/s</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Host System</div>
                        <div class="info-value">XEON 11</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">Data Center</div>
                        <div class="info-value">SkyLink Data Center BV, Eygelshoven, Netherlands</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">DDoS Protection</div>
                        <div class="info-value">Aurologics</div>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="info-text">
                        <div class="info-label">IP Address</div>
                        <div class="info-value">45.147.7.220</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="social">
            <h2><i class="fas fa-users"></i> Social Media</h2>
            <p>Connect with me on various social platforms.</p>

            <div class="social-links">
                <a href="https://www.instagram.com/the_cat_art1/" target="_blank" class="social-link instagram">
                    <i class="fab fa-instagram" style="color: #E1306C;"></i>
                    <span>Instagram</span>
                </a>
                <a href="https://www.tiktok.com/@the_cat_art1" target="_blank" class="social-link tiktok">
                    <i class="fab fa-tiktok" style="color: #69C9D0;"></i>
                    <span>TikTok</span>
                </a>
                <a href="https://discordapp.com/users/766654704419078164" target="_blank" class="social-link discord">
                    <i class="fab fa-discord" style="color: #7289DA;"></i>
                    <span>Discord</span>
                </a>
                <a href="https://github.com/TheCatArt" target="_blank" class="social-link github">
                    <i class="fab fa-github" style="color: #f5f5f5;"></i>
                    <span>GitHub</span>
                </a>
                <a href="https://www.reddit.com/user/The_Cat_Art/" target="_blank" class="social-link reddit">
                    <i class="fab fa-reddit" style="color: #FF4500;"></i>
                    <span>Reddit</span>
                </a>
                <a href="https://open.spotify.com/user/t5iunl8ilymqbf1yu2r72dz77" target="_blank" class="social-link spotify">
                    <i class="fab fa-spotify" style="color: #1DB954;"></i>
                    <span>Spotify</span>
                </a>
                <a href="https://twitter.com/TheCatArt1" target="_blank" class="social-link twitter">
                    <i class="fab fa-twitter" style="color: #1DA1F2;"></i>
                    <span>Twitter</span>
                </a>
                <a href="https://steamcommunity.com/id/the_cat_art" target="_blank" class="social-link steam">
                    <i class="fab fa-steam" style="color: #171a21;"></i>
                    <span>Steam</span>
                </a>
                <a href="https://youtube.com/@the_cat_art" target="_blank" class="social-link youtube">
                    <i class="fab fa-youtube" style="color: #FF0000;"></i>
                    <span>YouTube</span>
                </a>
                <a href="https://www.twitch.tv/the_cat_art" target="_blank" class="social-link twitch">
                    <i class="fab fa-twitch" style="color: #9146FF;"></i>
                    <span>Twitch</span>
                </a>
                <a href="https://last.fm/user/The_Cat_Art" target="_blank" class="social-link lastfm">
                    <i class="fab fa-lastfm" style="color: #d51007;"></i>
                    <span>Last.fm</span>
                </a>
                <a href="https://tidal.com/user/195981518" target="_blank" class="social-link tidal">
                    <i class="fa-tidal" style="color: #00FFFF;"></i>
                    <span>Tidal</span>
                </a>
                <a href="https://at.pinterest.com/the_cat_art/" target="_blank" class="social-link pinterest">
                    <i class="fab fa-pinterest" style="color: #E60023;"></i>
                    <span>Pinterest</span>
                </a>
                <a href="https://bsky.app/profile/thecatart1.bsky.social" target="_blank" class="social-link bluesky">
                    <i class="fas fa-cloud" style="color: #0085FF;"></i>
                    <span>Bluesky</span>
                </a>
                <a href="https://unity.com/" target="_blank" class="social-link unity">
                    <i class="fab fa-unity" style="color: #000000;"></i>
                    <span>Unity</span>
                </a>
                <a href="https://vrchat.com/home/user/usr_a56cfdb3-c82e-4701-b130-ed552d5c0d0c" target="_blank" class="social-link vrchat">
                    <i class="fas fa-vr-cardboard" style="color: #1B2838;"></i>
                    <span>VRChat</span>
                </a>
                <a href="https://beatleader.com/u/the_cat_art" target="_blank" class="social-link beatleader">
                    <i class="fas fa-music" style="color: #ff0066;"></i>
                    <span>BeatLeader</span>
                </a>
                <a href="https://stackoverflow.com/users/27346375/the-cat-art" target="_blank" class="social-link stackoverflow">
                    <i class="fab fa-stack-overflow" style="color: #f48024;"></i>
                    <span>StOv</span>
                </a>

                <style>
                    .social-link.unity:hover {
                        background: linear-gradient(45deg, #000000, #FFFFFF);
                        border-color: #000000;
                    }

                    .social-link.vrchat:hover {
                        background: linear-gradient(45deg, #1B2838, #00D4FF);
                        border-color: #00D4FF;
                    }

                    .social-link.beatleader:hover {
                        background: linear-gradient(45deg, #ff0066, #ff6699);
                        border-color: #ff0066;
                    }

                    .social-link.stackoverflow:hover {
                        background: linear-gradient(45deg, #f48024, #ff9500);
                        border-color: #f48024;
                    }

                    .social-link.unity:hover i,
                    .social-link.vrchat:hover .vrchat-logo,
                    .social-link.beatleader:hover .beatleader-logo,
                    .social-link.stackoverflow:hover i {
                        text-shadow:
                                -1px -1px 0 #000,
                                1px -1px 0 #000,
                                -1px 1px 0 #000,
                                1px 1px 0 #000,
                                0 0 3px rgba(0,0,0,0.8);
                        filter: brightness(1.2) contrast(1.1);
                    }

                    .social-link.unity:hover i {
                        color: #ffffff !important;
                    }

                    .social-link.vrchat:hover i {
                        color: #ffffff !important;
                    }

                    .social-link.beatleader:hover i {
                        color: #ffffff !important;
                    }

                    .social-link.stackoverflow:hover i {
                        color: #ffffff !important;
                    }
                </style>
            </div>
        </div>

        <div class="card" id="friends">
            <h2><i class="fas fa-heart"></i> Friends</h2>
            <p>Check out my friends' awesome websites and projects.</p>

            <div class="friends-links">
                <a href="https://voltox.org" class="friend-link">
                    <i class="fas fa-user-circle"></i>
                    <span>Voltox</span>
                </a>
                <a href="https://themcraft.com" class="friend-link">
                    <i class="fas fa-user-circle"></i>
                    <span>TheMCraft</span>
                </a>
                <!-- Add more friends if or smth like that needed -->
            </div>
        </div>

        <div class="card" id="about">
            <h2><i class="fas fa-user"></i> About Me</h2>
            <p>A little bit about who I am and what I do.</p>


            <div style="margin-top: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <p style="flex: 1; margin: 0; line-height: 1.6;">
                        Hi there! I'm Cat, a 16 year old full stack developer and tech enthusiast based in Klagenfurt, Austria.
                        I'm passionate about gayming, VR in general, coding, and traveling :3
                    </p>
                    <div class="pride-badge" style="flex-shrink: 0; border-radius: 6px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.2); animation: subtle-pulse 3s infinite alternate; transform: rotate(0deg) scale(1.2);">
                        <img src="https://badge.les.bi/88x31/gay/rainbow/diagonal.png" alt="Pride Badge" width="88" height="31">
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <p style="flex: 1; margin: 0; line-height: 1.6;">
                        I love spending time with my <span id="secret-trigger" class="secret-link">boyfriend</span>, diving into new tech just for fun,
                        and soaking up the peaceful vibes around Klagenfurt. Whether it's a cozy night
                        in with music or a spontaneous adventure outdoors, I’m all about keeping things curious and meaningful.
                    </p>
                    <div class="orange-heart" style="flex-shrink: 0; font-size: 2.5rem; color: #ff8c00; animation: heart-pulse 2.5s infinite alternate; text-shadow: 0 0 15px rgba(255, 140, 0, 0.6);">
                        🧡
                    </div>
                </div>

                <style>
                    @keyframes heart-pulse {
                        0% {
                            transform: scale(1);
                            text-shadow: 0 0 15px rgba(255, 140, 0, 0.6);
                        }
                        100% {
                            transform: scale(1.1);
                            text-shadow: 0 0 25px rgba(255, 140, 0, 0.8), 0 0 30px rgba(255, 140, 0, 0.4);
                        }
                    }
                </style>
                <div style="margin-top: 2rem;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">Skills & Interests</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Software Development</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Web Development</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Networking</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Cybersecurity</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Linux</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Rhythm Games</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Music</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Drawing</span>
                        <span style="background: rgba(40, 40, 60, 0.5); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem;">Photography</span>
                    </div>
                </div>
            </div>

            <style>
                @keyframes subtle-pulse {
                    0% {
                        transform: rotate(0deg) scale(1.2);
                        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                    }
                    100% {
                        transform: rotate(0deg) scale(1.25);
                        box-shadow: 0 8px 20px rgba(138, 43, 226, 0.3);
                    }
                }
            </style>
        </div>


        <div class="card" id="games">
            <h2><i class="fas fa-gamepad"></i> Gaming</h2>
            <p>Check out my gaming profiles and what I'm currently playing.</p>

            <div style="margin-top: 1.5rem;">
                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fab fa-steam" style="color: #171a21;"></i>
                        Currently Playing
                    </h3>
                    <div id="steam-currently-playing" style="background: rgba(40, 40, 60, 0.5); border-radius: 12px; padding: 1.5rem; border: 1px solid rgba(255, 255, 255, 0.05);">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-spinner fa-spin" style="color: var(--accent-blue);"></i>
                            <span>Loading Steam status...</span>
                        </div>
                    </div>
                </div>


                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fab fa-discord" style="color: #7289DA;"></i>
                        Discord Profile
                    </h3>
                    <div style="background: rgba(40, 40, 60, 0.5); border-radius: 12px; padding: 1rem; border: 1px solid rgba(255, 255, 255, 0.05);">
                        <iframe
                                src="https://widgets.vendicated.dev/user?id=766654704419078164&theme=dark&banner=true&full-banner=true&rounded-corners=true&discord-icon=true&badges=true&guess-nitro=true&"
                                width="100%"
                                height="280"
                                style="border: none; border-radius: 8px; background: transparent;"
                                loading="lazy">
                        </iframe>


                        <div style="background: rgba(40, 40, 60, 0.5); border-radius: 12px; padding: 1rem; border: 1px solid rgba(255, 255, 255, 0.05); text-align: center;">
                            <iframe src="https://gamer2810.github.io/steam-miniprofile/?accountId=76561199187129606"
                                    width="100%"
                                    height="200"
                                    style="border: none; border-radius: 8px; background: transparent;"
                                    frameborder="0">
                            </iframe>
                        </div>
                    </div>
                </div>
    </main>

    <footer class="footer">
        <p>© 2025 The_Cat_Art. All rights reserved</p>
        <div class="footer-links">
            <a href="#" class="footer-link">Privacy Policy</a>
            <a href="#" class="footer-link">Terms of Service</a>
            <a href="#" class="footer-link">Contact</a>
        </div>
    </footer>
</div>

<script>

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

</script>
</body>
</html>



