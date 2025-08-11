@extends('template.main')

@section('meta-title')Контакты@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Контакты'])
    <div class="container page-contacts py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-uppercase mb-6">Общество консультантов</h2>
                <div class="row">
                    <div class="col-12 col-lg-6 left-col">
                        <div class="d-flex flex-column justify-content-between h-100">
                            <div class="d-flex" style="padding-bottom: 10px;">
                                <div style="width:22px">
                                    <i class="icon icon-phone icon-2x"></i>
                                </div>
                                <div class="ps-3">
                                    <b>Телефон:</b> <a href="tel:+79001234567">+7 900 123-45-67</a>
                                </div>
                            </div>
                            <div class="d-flex" style="padding-bottom: 10px;">
                                <div style="width:22px">
                                    <i class="icon icon-location icon-2x"></i>
                                </div>
                                <div class="ps-3">
                                    <b>Адрес офиса:</b> г. Примерск, ул. Лесная, 10
                                </div>
                            </div>
                            <div class="d-flex" style="padding-bottom: 10px;">
                                <div style="width:22px">
                                    <i class="icon icon-phone icon-2x"></i>
                                </div>
                                <div class="ps-3">
                                    <b>Адрес для почтовых отправлений:</b> 123456,<br class="d-block d-sm-none"> г. Примерск, ул. Лесная, 10, Общество консультантов
                                </div>
                            </div>
                            <div class="d-flex contacts-worktime" style="padding-bottom: 10px;">
                                <div style="width:22px">
                                    <i class="far fa-clock" style="color:#990606; font-size: 13px; border: 1.5px solid #990606; border-radius: 100%; padding: 3px 3px 3px 4px;"></i>
                                </div>
                                <div class="ps-3">
                                    <b>Время работы офиса:</b><br>понедельник - четверг с 09:00 до 18:00,<br class="d-block d-sm-none"> в пятницу с 09:00 до 16:00
                                </div>
                            </div>
                            <div class="d-flex contacts-email" style="padding-bottom: 10px;">
                                <div style="width:22px">
                                    <i class="far fa-envelope" style="color:#990606; font-size: 13px; border: 1.5px solid #990606; border-radius: 100%; padding: 4px 3px 3px 4px;"></i>
                                </div>
                                <div class="ps-3">
                                    <b>E-mail:</b> contact@example.org
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 right-col">
                        <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A0000000000000000000000000000000000000000000000000000000000000000&amp;source=constructor" width="100%" height="280" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
