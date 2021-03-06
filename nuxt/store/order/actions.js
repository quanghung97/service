import axios from '~/plugins/axios'

export default {
    async order({commit}, order) {
        await axios
            .post('/order', {
                name: order.name,
                address: order.address,
                phone: parseInt(order.phone),
                request: order.request
            })
            .then(response => {
                commit('SET_MESSAGE', response.data.message)
            })
            // eslint-disable-next-line no-unused-vars
            .catch(error => {
                //console.log(error)
            })
    }
}
