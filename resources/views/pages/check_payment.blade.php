@extends('template.main')

@section('meta-title')Оплата членских взносов@endsection

@section('content')
    @includeif('includes.page_title', ['page_title' => 'Оплата членских взносов'])

    <div class="container py-5">
        <div class="mb-5">
            <p class="text-uppercase"><strong>Размер взносов на 2027 год</strong></p>
            <div class="table-responsive">
                <table class="table table-membership table-check-payment">
                    <thead>
                    <tr>
                        <th scope="col">
                            <p><strong>Вид взноса</strong></p>
                        </th>
                        <th scope="col">
                            <p><strong>Для физических лиц</strong></p>
                        </th>
                        <th scope="col">
                            <p><strong>Для юридических лиц</strong></p>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <p>Вступительный взнос</p>
                        </td>
                        <td>
                            <p>Не предусмотрен</p>
                        </td>
                        <td>
                            <p>8 750 рублей</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Членский взнос за 2027 год</p>
                        </td>
                        <td>
                            <p>3 920 руб. в год</p>
                        </td>
                        <td>
                            <p>21 480 руб. в год</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-6 mt-3 text-center row payment-blank-download">
            <div class="payment-blank-download__left">
                <span>Бланк для оплаты членского взноса за 2027 год</span>
            </div>
            <div class="col-md-3 payment-blank-download__right">
                <a href="/images/blanks/blank_payment_2027.png" download="blank_payment_2027.png" class="btn btn-primary">Скачать</a>
            </div>
        </div>
        <div class="form-check-content" id="form_check_content">
            <div class="fw-bold text-uppercase mb-4 text-center">
                Проверить оплату членского взноса
            </div>
            <form action="{{ route('reestr_check_payment') }}" method="post" id="form_check">
                @csrf
                <input type="hidden" name="header" value="Информация об оплате членских взносов">
                <div class="px-md-6 px-2 row d-flex justify-content-center">
                    <div class="col-12 col-md-3 mb-md-0 mb-3 px-1">
                        <input type="text" name="last_name" class="form-control" value="@isset($response['last_name']){{ $response['last_name'] }}@endisset" placeholder="Фамилия" >
                    </div>
                    <div class="col-12 col-md-3 mb-md-0 mb-3 px-1">
                        <input type="text" name="name" class="form-control" value="@isset($response['name']){{ $response['name'] }}@endisset" placeholder="Имя" >
                    </div>
                    <div class="col-12 col-md-3 mb-md-0 mb-3 px-1">
                        <input type="text" name="surname" class="form-control" value="@isset($response['surname']){{ $response['surname'] }}@endisset" placeholder="Отчество" >
                    </div>
                    <div class="col-12 col-md-2 d-flex justify-content-start px-1">
                        <button type="submit" class="btn btn-primary ms-md-3 ms-0" id="check_pay_btn"><span>Проверить</span></button>
                    </div>
                </div>
            </form>
            <div class="modal fade form-modal" id="messModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" style="transform: translate(0, -50%);">
                    <div class="modal-content">
                        <div class="modal-header border-0 mb-0 p-0 justify-content-center">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="z-index:1;"></button>
                        </div>
                        <div class="modal-body px-5 pt-3 pb-4">

                        </div>
                    </div>
                </div>
            </div>
            @isset($response['status'])
                <div class="px-6 mt-4 row">
                    <div class="col-10 px-md-6">
                        @if($response['status'] === 'success')
                            <div class="text-success">На вашу почту {{ obfuscate_email($response['email']) }} отправлено письмо с информацией об уплате взносов, в случае смены Вами почты, просим связаться по телефону <a href="tel:+7 900 555-44-33">+7 900 555-44-33</a></div>
                        @elseif ($response['status'] === 'not_found')
                            <div class="text-danger">{{ ncl_name_def($response['full_name'], 1) }} нет в базе, проверьте корректность указанных Вами данных или свяжитесь с нами по телефону <a href="tel:+7 900 555-44-33">+7 900 555-44-33</a></div>
                        @elseif ($response['status'] === 'error')
                            <div class="text-danger">Возникла ошибка. Пожалуйста, попробуйте позднее.</div>
                        @elseif ($response['status'] === 'validate')
                            <div class="text-danger">Все поля обязательны для заполнения!</div>
                        @endif
                    </div>
                </div>
            @endisset
        </div>
        <div class="mt-6">
            <p><strong><span style="color:#990606">Оплата членских взносов физическими лицами</span></strong></p>
            <div class="membership-pay-indiv">
                <p>При вступлении в члены Общества консультантов уплачивается годовой членский взнос за текущий календарный год. Вступительный взнос для физических лиц не предусмотрен.</p>
                <p>Если физическое лицо вступает в организацию во втором полугодии в период с 3 июля по 28 декабря, членский взнос уплачивается в размере 1 960 рублей (1/2 от установленного размера за год).</p>
            </div>
            <p><strong>Сроки оплаты взносов физическими лицами</strong></p>
            <p>Оплата <strong>первого</strong> членского взноса физическими лицами производится при вступлении в организацию в течение 7 календарных дней с даты подачи заявления.</p>
            <p>В случае неуплаты первого членского взноса при вступлении в организацию, решение о приеме в члены аннулируется.</p>
            <p>Оплата <strong>второго</strong> и последующих годовых членских взносов производится физическими лицами до 27 января текущего года.</p>
            <p><strong><span style="color:#990606">Оплата взносов юридическими лицами</span></strong></p>
            <p>Юридические лица оплачивают вступительный и членский взносы в размере 100% от установленного размера, независимо от сроков вступления в организацию.</p>
            <p><strong>Сроки оплаты взносов юридическими лицами</strong></p>
            <p>Оплата вступительного взноса производится юридическими лицами однократно при вступлении в организацию в течение 6 календарных дней с даты принятия решения о приеме.</p>
            <p>Оплата <strong>первого</strong> членского взноса производится юридическими лицами при вступлении в организацию в течение 6 календарных дней с даты принятия решения о приеме.</p>
            <p>Оплата <strong>второго</strong> и последующих годовых членских взносов производится юридическими лицами до 27 января текущего года.</p>
        </div>
    </div>

@endsection
