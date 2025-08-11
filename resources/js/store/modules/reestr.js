const getDefaultState = () => {
    return {
        dataItems: []
    };
};

const state = getDefaultState();

const mutations = {
    SET_DATA_ITEMS(state, items) {
        state.dataItems = items;
    },
    RESET_STATE(state) {
        Object.assign(state, getDefaultState());
    },
    DELETE_DATA_ITEM(state, index) {
        state.dataItems.splice(index, 1);
    },
    DELETE_DATA(state, data) {
        let dataItems = state.dataItems;
        const valuesToRemove = new Set(data);
        state.dataItems = dataItems.reduce((acc, item) => {
            if (!valuesToRemove.has(item.id)) {
                acc.push(item);
            }
            return acc;
        }, []);
    }
};

const actions = {
    setDataItems({ commit }, items) {
        commit('SET_DATA_ITEMS', items);
    },
    resetState({ commit }) {
        commit('RESET_STATE');
    },
    deleteDataItem({ commit, state }, id) {
        const indexToRemove = state.dataItems.findIndex(item => item.id === id);
        if (indexToRemove !== -1) {
            commit('DELETE_DATA_ITEM', indexToRemove);
        }
    },
    deleteData({ commit, state }, data) {
        if(data.length > 0) commit('DELETE_DATA', data);
    }
};

const getters = {
    dataItems: state => state.dataItems
};

export default {
    state,
    mutations,
    actions,
    getters
};
