import Vue from "vue";
import { mount } from "@vue/test-utils";
import CompositionApi from "@vue/composition-api";
import CounterButton from "@/components/admin/test/CounterButton.vue";

Vue.use(CompositionApi);

describe("CounterButton.vue", () => {
    let wrapper: any;

    beforeEach(() => {
        wrapper = mount(CounterButton)
    })

    it.only("по умолчанию отображает count = 0", () => {
        const span = wrapper.find("span");
        expect(span.text()).toBe("0");
    })

    it("увеличивает count при клике", async () => {
        const button = wrapper.find("button");
        await button.trigger("click");
        const span = wrapper.find("span");
        expect(span.text()).toBe("1");
    })

    it("считает несколько кликов", async () => {
        const button = wrapper.find("button");
        await button.trigger("click");
        await button.trigger("click");
        await button.trigger("click");
        expect(wrapper.find('span').text()).toBe("3");
    })

    it("вызывает console.log при монтировании", () => {
        const consoleSpy = jest.spyOn(console, 'log').mockImplementation(() => {});
        mount(CounterButton);
        expect(consoleSpy).toHaveBeenCalledWith("Component mounted.");
        consoleSpy.mockRestore();
    })

    it("соответствует снэпшоту", () => {
        expect(wrapper.html()).toMatchSnapshot();
    })

})
