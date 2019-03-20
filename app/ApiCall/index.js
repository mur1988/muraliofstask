
import {
  Alert
} from 'react-native';

//Login API URL
const severURL = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=3e7cc266ae2b0e0d78e279ce8e361736&format=json&nojsoncallback=1&safe_search=1&text=';

export function SearchAPI(searchText) {
    console.log("searchText",searchText);
    return fetch(`${severURL+searchText+'&page=20'}`, {
    }).then((list) => {
      console.log(list);
      return  list.json();
    }).catch((error) => {
        console.log(error);
        Alert.alert('Network Error');
     });
 }
