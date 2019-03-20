import React, {Component} from "react";
import {Provider} from "react-redux";
import configureStore from "./app/Store/";
import Navigation from "./app/components/Navigation.js";
const store = configureStore();

export default class App extends Component {
  render() {
    return (
      <Provider store={store}>
        <Navigation/>
      </Provider>
    );
  }
}
