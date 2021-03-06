<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> Главная</a></li>
@if(backpack_user()->role === \App\Domain\Contracts\UserContract::TRANSLATE[\App\Domain\Contracts\UserContract::ADMINISTRATOR])

    <li class="nav-title"><span class="text-primary">Администратор</span></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('user') }}'><i class='nav-icon la la-users'></i> Пользователи</a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('category') }}'><i class='nav-icon las la-bars'></i> Категории</a></li>
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle font-weight-normal" href="#"><i class='nav-icon las la-globe'></i> Местоположение</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('country') }}'><i class='nav-icon las la-flag'></i> Страны</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('city') }}'><i class='nav-icon las la-map-marker'></i> Города</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('languages') }}'><i class='nav-icon las la-language'></i> Языки</a></li>
        </ul>
    </li>

    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle font-weight-normal" href="#"><i class="las la-id-card"></i> Контакты</a>
        <ul class="nav-dropdown-items">
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('contracts') }}'><i class="las la-address-card"></i> Договор оферты</a></li>
            <li class='nav-item'><a class='nav-link' href='{{ backpack_url('privacy') }}'><i class="las la-id-card-alt"></i> Политика конфиденциальности</a></li>
        </ul>
    </li>
@else
    <li class="nav-title"><span class="text-primary">Модератор</span></li>
@endif

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('booking') }}'><i class='nav-icon las la-sort'></i> Бронирование</a></li>

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('review') }}'><i class='nav-icon las la-comment'></i> Отзывы</a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle font-weight-normal" href="#"><i class='nav-icon las la-building'></i> Организации</a>
    <ul class="nav-dropdown-items">
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('organization') }}'><i class='nav-icon las la-building'></i> Организации</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('organizationtables') }}'><i class='nav-icon la la-border-all'></i> Секции</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('organizationtablelist') }}'><i class='nav-icon las la-table'></i> Столы</a></li>
        <li class='nav-item'><a class='nav-link' href='{{ backpack_url('organizationimage') }}'><i class='nav-icon las la-image'></i> Фото</a></li>
    </ul>
</li>
