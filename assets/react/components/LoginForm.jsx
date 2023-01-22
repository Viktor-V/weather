import React, {useContext, useState} from "react";
import {Context} from "../Index";
import {observer} from "mobx-react-lite";

const LoginForm = () => {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');

    const {store} = useContext(Context)

    return (
        <div>
            <input
                onChange={e => setUsername(e.target.value)}
                value={username}
                type="text"
                placeholder="Username"
            />

            <input
                onChange={e => setPassword(e.target.value)}
                value={password}
                type="text"
                placeholder="Password"
            />

            <button onClick={() => store.login(username, password)}>Login</button>
        </div>
    );
}

export default observer(LoginForm);
