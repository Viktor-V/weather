import React from "react";
import {observer} from "mobx-react-lite";

const WeatherDataOnImperialSystem = (props) => {
    return (
        <div>
            <ul>
                <li>City: {props.weather.city}</li>
                <li>Temperature: {props.weather.temperature} °C</li>
                <li>Wind speed: {props.weather.windSpeed} m/s</li>
            </ul>
        </div>
    );
}

export default observer(WeatherDataOnImperialSystem);
