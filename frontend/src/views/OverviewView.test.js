import OverviewView from './OverviewView.vue'
import { mount } from "@vue/test-utils"
import Vuex from 'vuex'

test("HelloWorld Component renders the correct text", () => {

    let store = new Vuex.Store({
        modules: {
            overview: {
                state: {
                    welcome: 'yoyoyo'
                }
            }
        }

    })

    const wrapper = mount(OverviewView, {store});
    expect(wrapper.text()).toBe("Hello there!");
});