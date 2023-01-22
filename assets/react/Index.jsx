import Store from "./store/store";
import React, {createContext, useContext} from "react";
import App from "./App";

const store = new Store();

export const Context = createContext({store});

export default function () {
    const {store} = useContext(Context);

    return (
        <Context.Provider value={{store}}>
            <App/>
        </Context.Provider>
    )
}
