
import { createStore, createLogger } from 'vuex'
import overview from './modules/overview'
import todoList from './modules/todo-list'

const debug = process.env.NODE_ENV !== 'production'

export default createStore({
    modules: {
        overview,
        todoList
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
})
