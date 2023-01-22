import React, {useContext, useEffect, useState} from "react";
import LoginForm from "./components/LoginForm";
import {Context} from "./Index";
import {observer} from "mobx-react-lite";
import WeatherDataOnInternationalSystem from "./components/WeatherDataOnInternationalSystem";
import WeatherDataOnImperialSystem from "./components/WeatherDataOnImperialSystem";

const App = () => {
    const {store} = useContext(Context);

    const [isInternationalSystem, setIsInternationalSystem] = useState(true);

    useEffect(() => {
        if (localStorage.getItem('token')) {
            store.checkAuth();
        }
    }, []);

    if (!store.isAuth) {
        return (<LoginForm/>)
    }

    return (
        <div>
            <button onClick={() => store.getWeather('Riga', false)}>Current weather - Riga</button>
            <button onClick={() => store.getWeather('New York', false)}>Current weather - New York</button>
            <button onClick={() => store.getWeather('Riga', true)}>Average weather last 7 days - Riga</button>
                <button onClick={() => store.getWeather('New York', true)}>Average weather last 7 days - New York</button>


            {isInternationalSystem ? (
                <button onClick={() => setIsInternationalSystem(false)}>Imperial System</button>
            ) : (
                <button onClick={() => setIsInternationalSystem(true)}>International System</button>
            )}

            <button onClick={() => store.logout()}>Logout</button>

            {store.message &&
                <p>{store.message}</p>
            }

            {store.weather && isInternationalSystem &&
                <WeatherDataOnInternationalSystem weather={store.weather}/>
            }

            {store.weather && !isInternationalSystem &&
                <WeatherDataOnImperialSystem weather={store.weather}/>
            }
        </div>
    );
}

export default observer(App);
