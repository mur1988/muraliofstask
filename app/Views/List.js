/*This is an Example of Grid View in React Native*/
import React, { Component } from 'react';
//import rect in our project
import {
  StyleSheet,
  View,
  Text,
  FlatList,
  ActivityIndicator,
  Image,
  Alert,
  TouchableOpacity,
  TouchableHighlight
} from 'react-native';
import { connect } from "react-redux"
import  consts from '../utilities/utilitiesConstants';


export class List extends Component {
  constructor() {
    super();
    this.state = {
      dataSource: {},
      listsize:''
    };
  }

  componentWillMount() {
    var that = this;
    const {state} = this.props.navigation;
    var dataList = state.params.data.photos.photo;
    console.log(dataList);
    that.setState({
      dataSource: dataList,
      listsize:dataList.length
    });
  }

  goToNextScreen = (title,url) => {
    this.props.navigation.navigate(consts.IMAGE_SCREEN,{selectUrl:url,selecttittle:title});
  }

  renderView() {
          if (this.state.listsize === 0) {
            return (<Text style={{textAlign: 'center'}} >No Record Found</Text>)
          }else{
            return <FlatList
              data={this.state.dataSource}
              renderItem={({ item }) => (
                <View style={{ flex: 1, flexDirection: 'column', margin: 1 }}>
                <TouchableHighlight onPress={() => this.goToNextScreen(item.title,'https://farm'+item.farm+'.staticflickr.com/'+item.server+'/'+item.id+'_'+item.secret+'_n.jpg')}>
                  <Image style={styles.imageThumbnail} source={{ uri: 'https://farm'+item.farm+'.staticflickr.com/'+item.server+'/'+item.id+'_'+item.secret+'_n.jpg'}} />
                  </TouchableHighlight>
                </View>
              )}
              numColumns={3}
              keyExtractor={(item, index) => index}
            />;
          }
  }
  render() {
    return (
      <View style={styles.MainContainer}>
      {this.renderView()}
      </View>
    );
  }
}

const styles = StyleSheet.create({
  MainContainer: {
    justifyContent: 'center',
    flex: 1,
    paddingTop: 30,
  },
  imageThumbnail: {
    justifyContent: 'center',
    alignItems: 'center',
    height: 100,
  },
});

const mapStateToProps = (state) => ({
    list: state.get('list')
});
export default connect(mapStateToProps)(List);
