import React, { Component } from 'react';

import { StyleSheet, View, Image } from 'react-native';

export default class App extends Component<{}> {

  constructor() {
    super();
    this.state = {
      selectUrl: '',
    };
  }


  componentDidMount() {
    console.disableYellowBox = true;
    var that = this;
    const {state} = this.props.navigation;
    var selectUrlString = state.params.selectUrl;
    var selecttittlestring = state.params.selecttittle;
    that.setState({
      selectUrl: selectUrlString,
    });
  }

 render() {

   return (
     <Image source={ {uri: this.state.selectUrl} } style={styles.mainContainer} >
     </Image>
  );
 }
}


const styles = StyleSheet.create({
 mainContainer: {
   flex: 1,
   justifyContent: 'center',
   alignItems: 'center',
   width: null,
   height: null,
 }
});
