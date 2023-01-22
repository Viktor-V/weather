import React from "react";
import {observer} from "mobx-react-lite";

const WeatherDataOnInternationalSystem = (props) => {
    const temperature = props.weather.temperature * (9/5) + 32;
    const windSpeed = props.weather.windSpeed * 3.2808;

    return (
        <div>
            <ul>
                <li>City: {props.weather.city}</li>
                <li>Temperature: {temperature} °F</li>
                <li>Wind speed: {windSpeed} ft/s</li>
            </ul>
        </div>
    );
}

export default observer(WeatherDataOnInternationalSystem);
