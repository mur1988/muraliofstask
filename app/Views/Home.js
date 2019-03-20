/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 *
 *
 */
import React, {Component} from 'react';
import {connect} from "react-redux";

import {
  Platform,
  StyleSheet,
  Text,
  TextInput,
  TouchableHighlight,
  ActivityIndicator,
  View,
  FlatList,
  Alert,
  Spinner,
  Dimensions} from 'react-native';
import * as homeActions from '../Action/';
import * as searchReducer from '../Reducers/';
import  consts from '../utilities/utilitiesConstants';

export  class Home extends Component<Props> {

  constructor() {
      super();
      this.state = {
       loading : false,
       searchResult:''
  }
}

static navigationOptions = {
      title: 'Home'
};

onSearchPress = () => {
  if (this.state.searchResult === '') {
    this.setState({loading : false})
    Alert.alert('Please Enter Text');
  } else {
   this.setState({loading : true})
   this.props.dispatch(homeActions.getList(this.state.searchResult));
  }
}

UNSAFE_componentWillReceiveProps (nextProps) {
      this.setState({loading : false})
      const { navigate, goBack } = this.props.navigation;
      const data = this.props.home.get('fetched');
      var chatdata = nextProps.home.get('chatdata');
      console.log("componentWillReceiveProps",chatdata);
      if(data){
         this.props.navigation.navigate(consts.LIST_SCREEN,{data:chatdata});
      }
}

searchOnTextChange = (searchResultText) => this.setState({ searchResult: searchResultText });

renderButtonOrSpinner() {
        if (this.state.loading) {
          return <ActivityIndicator size='small'/>;
        }else{
          return (<TouchableHighlight onPress={ this.onSearchPress }><Text style={styles.buttonContainer}>Search</Text></TouchableHighlight>)
        }
}

render() {
    return (
      <View style={styles.container}>
          <View style={styles.childContainer1}>
                  <TextInput style={styles.inputStyle}
                  autoCorrect={false}
                  secureTextEntry = {false}
                  placeholder="Enter Text"
                  value={this.state.searchResult}
                  onChangeText={this.searchOnTextChange}
                  underlineColorAndroid='transparent'/>
                  <View style={styles.childContainer2} >
                    {this.renderButtonOrSpinner()}
                  </View>
          </View>
     </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'white',
    flexDirection : 'column'
  },

  inputStyle: {
    height : 40,
    width: Dimensions.get('window').width -20 ,
    borderColor : 'orange',
    borderWidth : 2,
    marginTop: 10,
    marginBottom : 10,
  },

  flatview: {
    justifyContent: 'center',
    paddingTop: 30,
    borderColor : 'black',
    borderRadius: 2,
  },

  childContainer1 : {
    flex: 0.3,
    justifyContent: 'flex-start',
    alignItems: 'center',
  },

  email: {
    borderColor : 'black',
    borderWidth : 1,
    color: 'black',
    textAlign: 'center',
  },

  buttonContainer:{
    alignItems : 'center',
    justifyContent : 'center',
    height : 40,
    width: '100%' ,
    fontSize: 18,
    color : 'white',
    padding:'2%',
    backgroundColor: 'orange',
  },

  buttonContainer1:{
    height : 40,
    width: '50%' ,
    backgroundColor: 'red',
  },

  verticalContainer: {
    borderColor : 'black',
    borderWidth : 1,
    color: 'black',
    flex : 0.5,
    textAlign: 'center',
  },

});

function mapStateToProps(state) {
  return {
    home: state.get('home'),
  }
}
export default connect(mapStateToProps)(Home)
