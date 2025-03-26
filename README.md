# iPad Dashboard

A lightweight dashboard designed to repurpose old iPads into smart display frames. Shows time, weather, and customizable backgrounds while maintaining compatibility with first-generation iPads.

## Features

- Real-time clock display
- Current weather information for Aguascalientes, MX
- Dynamic background image support
- Brightness controls via overlay
- Touch-optimized interface
- Automatic updates for weather and configuration

## Requirements

- Docker
- Docker Compose
- Web browser (optimized for iPad)
- Internet connection (for weather updates)

## Installation & Running

1. Clone the repository:
```bash
git clone <repository-url>
cd dashboard
```

2. Configure environment:
   - Place background images in the public directory
   - Update config.json with desired settings
   - Adjust weather location in WeatherService.php if needed

3. Start the application:
```bash
docker-compose up -d
 ```

The dashboard will be available at:

```plaintext
http://localhost:8000
 ```

## Configuration
...
- backgroundImage : Image file name from public directory
- overlayOpacity : Screen darkness (0-1, where 0 is brightest)

## Usage
- Use +/- buttons to adjust screen brightness
- Weather updates every 5 minutes
- Configuration checks every 10 seconds for changes

## Development
To make changes while running:
1. Edit files in the project directory
2. Changes will reflect immediately due to volume mounting
3. No container rebuild needed for most changes

## Project Structure
```plaintext
dashboard/
├── public/          # Web root
├── app/             # Application code
│   ├── controllers/ # API controllers
│   ├── services/    # Business logic
│   └── templates/   # HTML templates
├── config.json      # Configuration file
├── Dockerfile      # Container definition
└── docker-compose.yml
 ```

## Troubleshooting
- If weather doesn't update, check internet connection
- For image issues, ensure files exist in public directory
- Container logs can be viewed with: docker-compose logs

## License
MIT License