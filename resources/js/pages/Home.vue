<template>
    <div class="wrap">
        <section class="top">
            <p class="subtitle"> Введите количетсво дней (от 30) для заполнения базы данных</p>
            <input @change="checkCountDay" v-model="countDay" type="text">
            <button @click="fiilBD">заполнить БД</button>
        </section>
        <hr>
        <section v-if="valuteList.length > 0">
            <p class="subtitle"> Примените фильтр для выдачи из БД</p>
            <select v-model="filter.valuteID">
                <option :value="null">не выбрано</option>
                <option v-for="(valute, index) in valuteList" :value="valute.valuteID">{{valute.valuteID}}</option>
            </select>
                <date-picker v-model="filter.date" type="date" value-type="format" format="YYYY-MM-DD" range placeholder="начало - конец"></date-picker>
            <button @click="getFilter">сортировка</button>
            <p>{{errors}}</p>
            <div class="result__table" v-if="resultList.length > 0" v-for="(result, index) in resultList">
                <p>{{result.valuteID}}</p>
                <p>{{result.numCode}}</p>
                <p>{{result.сharCode}}</p>
                <p>{{result.value}}</p>
                <p>{{result.date}}</p>
            </div>
        </section>
    </div>
</template>

<script>
  import DatePicker from 'vue2-datepicker';
  import 'vue2-datepicker/index.css';

    export default {
        name: 'Home',
        data() {
            return {
                countDay: 30,
                valuteList: [],
                resultList: [],
                errors: null,
                filter: {
                    valuteID: null,
                    date: null,
                }
            } 
        },
        created () {
            this.getValute();
        }, 
        methods: {
            checkCountDay() {
                if (isNaN(parseInt(this.countDay)) || parseInt(this.countDay) < 30) {
                    this.countDay = 30;
                }
            },
           fiilBD() {
               let vm = this;
               axios.get(this.$root.$api.Currency.fillBD, { params: {
                   count_day: this.countDay
               }}).then(response => {
                   if (response.data.success == true) {
                       vm.getValute();
                   }
               })
           },
           getFilter() {
               let vm = this;
               if (this.filter.date == null) {
                   this.filter.date = [];
                   this.filter.date.push(null)
                   this.filter.date.push(null)
               }
               axios.get(this.$root.$api.Currency.getFilter, {params: {
                   valuteID: this.filter.valuteID,
                   date_from: this.filter.date[0],
                   date_to: this.filter.date[1]
               }}).then(response => {
                   if (response.data.valute.length == 0) {
                       vm.errors = 'поиск не дал результатов'
                   } else {
                        vm.errors = null;
                    vm.resultList = response.data.valute;
                   }

               })
           },
           getValute() {
               let vm = this;
               axios.get(this.$root.$api.Currency.getValute).then(response => {
                   if (response.data.valute.length > 0) {
                       vm.valuteList = response.data.valute;
                   }
               })
           }
        },
        components: { 
            DatePicker 
        },
    }
</script>


<style>
   
</style>

