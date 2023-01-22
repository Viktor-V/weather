import React from "react";
import {observer} from "mobx-react-lite";

const WeatherDataOnImperialSystem = (props) => {
    return (
        <div>
            <ul>
                <li>City: {props.weather.city}</li>
                <li>Temperature: {props.weather.temperature.toFixed(2)} °C</li>
                <li>Wind speed: {props.weather.windSpeed.toFixed(2)} m/s</li>
            </ul>
        </div>
    );
}

export default observer(WeatherDataOnImperialSystem);
