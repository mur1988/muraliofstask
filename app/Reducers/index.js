import * as actions from "../constants";

export default function SearchReducer(state, action = {}) {
  switch (action.type) {
      case actions.ACTION_HOME_DEFAULT_LIST_ERROR:{
          return state.withMutations(state => state
            .set('chatdata', {})
            .set('fetched', false));
           }
      case actions.ACTION_HOME_DEFAULT_LIST_SUCCESS:{
          const data = action.list;
          console.log('! SearchReducer', data)
          return state.withMutations(state => state
            .set('chatdata', data)
            .set('fetched', true));
           }
      default:
      return state;
  }

}
