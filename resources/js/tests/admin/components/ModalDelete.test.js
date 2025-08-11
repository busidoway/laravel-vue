import Vue from "vue";
import { shallowMount } from '@vue/test-utils'
import ModalDelete from '../../../components/admin/components/ModalDelete.vue'

describe('ModalDelete.vue', () => {
    it('Отображает правильный заголовок', () => {
        const title = 'Тестовый объект';
        const wrapper = shallowMount(ModalDelete, {
            propsData: { title }
        })

        expect(wrapper.find('h2').text()).toBe(`Удалить ${title}?`)
    });

    // it('вызывает событие delete-confirm с правильным id при клике', async () => {
    //     const id = 123;
    //     const wrapper = shallowMount(ModalDelete, {
    //         propsData: { id }
    //     })
    //
    //     wrapper.find('#delete_item').trigger('click');
    //
    //     expect(wrapper.emitted('delete-confirm')).toBeTruthy();
    //     expect(wrapper.emitted('delete-confirm')[0]).toEqual([id]);
    // })
})
