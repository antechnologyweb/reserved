<template>
    <Header></Header>
    <profile-section></profile-section>
    <comment :item="item" ></comment>
    <div class="container-fluid mb-md-5">
        <div class="container p-0">
            <template v-if="items.length > 0">
                <div class="row">
                    <div class="col-12">
                        <h2 class="history-title">История бронирования</h2>
                        <p class="history-description">
                            Здесь вы можете просматривать вашу историю бронировани в заведениях.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 d-flex justify-content-between settings-item align-items-center" v-for="(item,key) in items" :key="key">
                                <div class="d-flex" v-if="item">
                                    <div class="history-card-icon history-icon mr-md-3"></div>
                                    <div>
                                        <div class="history-font font-weight-bold">
                                            <a :href="'/home/'+item.organization.id" class="p-0 text-dark">{{item.organization.title}}</a> • <span class="text-secondary" v-if="item.organization_tables.title">{{item.organization_tables.title}}</span>
                                        </div>
                                        <p class="history-font text-secondary m-0">{{item.date}} • {{item.time}}</p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="history-status history-status-waiting" v-if="item.status === 'CHECKING'" @click="initPayment(key)">
                                        Ожидает оплаты {{item.price}} KZT
                                    </div>
                                    <div class="history-status history-status-review" v-else-if="item.status === 'COMPLETED' && item.comment === 'on'" @click="comment(key)"  data-toggle="modal" data-target="#comment_modal">
                                        <div class="history-status-icon history-status-icon-pen"></div>Оставить отзыв
                                    </div>
                                    <div class="history-status history-status-last" v-else-if="item.status === 'COMPLETED'">
                                        Завершено {{item.price}} KZT
                                    </div>
                                    <div class="history-status history-status-success" v-else>
                                        Забронировано {{item.price}} KZT
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="container-fluid">
                    <div class="container pt-md-5">
                        <div class="col-12 d-flex justify-content-center mt-5 mb-3">
                            <div>
                                <img src="/img/logo/calendar.svg" width="100">
                            </div>
                        </div>
                        <div class="col-12 mt-3 mb-5">
                            <h2 class="text-center history-empty-title font-weight-bold">Список пуст</h2>
                            <p class="text-center history-empty-description text-secondary">Ваша история заказов будет отображаться здесь</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <Footer-menu></Footer-menu>
    <Footer></Footer>
</template>

<script>
import Header from "./header/Header";
import Footer from "./footer/Footer";
import ProfileSection from './sections/ProfileSection';
import FooterMenu from './footerMenu/FooterMenu';
import Comment from './modal/comment';
export default {
    components: {
        Header,
        Footer,
        ProfileSection,
        FooterMenu,
        Comment
    },
    name: "History",
    data() {
        return {
            status: true,
            item: {},
            items: [],
            user: false,
            paginate: 1
        }
    },
    created() {
        this.getUser();
    },
    mounted() {
        this.getBookings();
    },
    methods: {
        comment: function(key) {
            this.storage.modal  =   true;
            this.item   =   this.items[key];
        },
        open: function(url) {
            let a = document.createElement("a");
            document.body.appendChild(a);
            a.target    =   '_blank';
            a.style = 'display: none';
            a.href = url;
            a.click();
            document.body.removeChild(a);
        },
        initPayment: function(key) {
            let payment =   this.items[key];
            if (!payment.payment_id) {
                this.open(payment.payment);
            } else {
                this.open('/form/'+payment.id);
            }
        },
        getUser: function() {
            if (this.storage.token && sessionStorage.user) {
                this.user   =   JSON.parse(sessionStorage.user);
            } else {
                window.location.href = '/home';
            }
        },
        getBookings: function() {
            if (this.user && this.status) {
                this.status =   false;
                let self    =   this;
                axios.get('/api/booking/user/'+this.user.id+'?paginate='+this.paginate)
                    .then(response => {
                        let data    =   response.data;
                        if (data.hasOwnProperty('data')) {
                            this.items  =   data.data;
                            this.status =   true;
                            setTimeout(function() {
                                self.getBookings();
                            },2000);
                        }
                    }).catch(error => {
                        this.status =   true;
                        setTimeout(function() {
                            self.getBookings();
                        },2000);
                    });
            }
        }
    }
}
</script>

<style lang="scss">
    @import '../../css/profile/history.scss';
</style>
