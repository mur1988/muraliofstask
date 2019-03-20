import * as actions from "../constants";

export function getList(searchtext) {
  return {
    type: actions.ACTION_HOME_DEFAULT_LIST,
    searchtxt: searchtext
  };
}

export function setError(error) {
  return {
    type: actions.ACTION_HOME_DEFAULT_LIST_ERROR,
    error: error
  };
}


export function setListSuccess(list) {
  return {
    type: actions.ACTION_HOME_DEFAULT_LIST_SUCCESS,
    list: list
  };
}
