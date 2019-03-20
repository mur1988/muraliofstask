/**
 * Created by Murali on 2/9/18.
 */


 import {call, put, take} from "redux-saga/effects";
 import * as actions from "../constants";
 import * as Api from "../ApiCall/";
 import * as homeActions from "../Action/";


 function* getList(searchtxt) {
   try {
    var list = yield call(Api.SearchAPI,searchtxt);
    if (list.length !== 0) {
       yield put(homeActions.setListSuccess(list));
       return list;
     } else {
       yield put(homeActions.setError(list));
       return undefined
     }
   } catch (error) {
     yield put(homeActions.setError(error));
   }
 }

 export function* listFlow() {
     while (true) {
       const {searchtxt} = yield take(actions.ACTION_HOME_DEFAULT_LIST);
       yield call(getList, searchtxt);
     }
 }
