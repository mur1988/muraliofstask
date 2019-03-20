import React, {Component} from "react";
import {connect, Provider} from "react-redux";
import configureStore from "../Store/";
import { createStackNavigator, createAppContainer } from 'react-navigation';
import Home from "../Views/Home";
import List from "../Views/List";
import Image from "../Views/ImageView";

const store = configureStore();

const Routes = {
 Home: {screen: Home},
 List: {screen: List},
 Image: {screen: Image}
};

const Navigator = createStackNavigator(
 Routes,
 {headerMode: 'screen'}
);

const AppContainer = createAppContainer(Navigator);


export class Navigation extends Component {
 render() {
   return (
     <Provider store={store}>
        <AppContainer/>
     </Provider>
   );
 }

}

function mapStateToProps(state) {
 return {
   home: state.home
 }
}
export default connect(
 mapStateToProps)(Navigation);
