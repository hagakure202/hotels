<template>
    <b-container>
        <b-row class="text-center">
            <b-col cols="1">STATUS</b-col>
            <b-col cols="3">Accommodation</b-col>
            <b-col cols="1">Number of rooms</b-col>
            <b-col cols="2">ROOM TYPE</b-col>
            <b-col cols="1">CHECK-IN DATE</b-col>
            <b-col cols="1">NTS</b-col>
            <b-col cols="1">PRICE €</b-col>
            <b-col cols="1">COST €</b-col>
            <b-col cols="1"></b-col>
        </b-row>
        <b-row class="text-center mb-1" v-for="item in items" :key="item.age">
            <b-col cols="1">
                <button :id="`popover-reactive-${item.id}`" class="status"  :style="'background-color: #'+item.status_color"></button>

                <b-popover
                        :target="`popover-reactive-${item.id}`"
                        triggers="click"
                        ref="popover"
                        :show.sync="item.status_show"
                >
<!--                    <template #title>-->
<!--                        <b-button @click="onClose" class="close" aria-label="Close">-->
<!--                            <span class="d-inline-block" aria-hidden="true">&times;</span>-->
<!--                        </b-button>-->
<!--                        Choose status-->
<!--                    </template>-->

                    <div>
                        <b-form-radio-group>
                            <div class="status" v-model="item.status" v-for="option in statuses"
                                 :style="'background-color: #'+option.d_color_code">
                                <b-form-radio :key="option.d_id"
                                              :value="option.d_name"
                                              v-on:change="change_status(item.id,option.d_id,option.d_color_code)"   :button-variant="option.buttonVariant">


                                </b-form-radio>
                            </div>
                        </b-form-radio-group>

                    </div>
                </b-popover>

            </b-col>
            <b-col cols="3">
                <b-form-select v-model="item.accommodation" :options="accommodation" value-field="o_id"
                               v-on:change="getPrice(item.id)"  text-field="o_name"></b-form-select>
            </b-col>
            <b-col cols="1">
                <b-form-input v-model="item.number_of_rooms" v-on:change="getPrice(item.id)" type="number"></b-form-input>
            </b-col>
            <b-col cols="2">
                <b-form-select v-model="item.room_type" v-on:change="getPrice(item.id)" :options="room_type" value-field="d_id" text-field="d_name"
                ></b-form-select>
            </b-col>


            <b-col cols="1">

                <b-form-datepicker v-model="item.check_in_date" id="datepicker1" @input="getPrice(item.id)"
                                   button-only
                                     locale="en"  :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   right
                                   class="mb-2">
                    <br/>


                </b-form-datepicker>
            </b-col>
            <b-col cols="1">
                <b-form-input v-model="item.nts" v-on:change="getPrice(item.id)" type="number"></b-form-input>
            </b-col>
            <b-col cols="1">
                <b-form-input v-model="item.price" type="number"></b-form-input>
            </b-col>
            <b-col cols="1">
                <b-form-input v-model="item.cost" type="number"></b-form-input>
            </b-col>
            <b-col cols="1">
                <b-button v-on:click="remove(item.id)" variant="danger">X</b-button>
            </b-col>
        </b-row>
        <b-row>
            <b-col cols="4" >
                <br/>
                <b-button v-on:click="add()" variant="primary">ADD</b-button>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
  import axios from 'axios'

  export default {
    data () {
      return {
        items_count: 0,
        items: [],
        statuses: [],
        accommodation: [],
        room_type: [],
        selected: null,
        popoverShow: false
      }
    },

    created () {
      const self = this

      axios
        .get('/api/hotels')
        .then(response => {
          this.statuses = response.data.dic_status
          this.accommodation = response.data['Accommodation suppliers']
          this.room_type = response.data.dic_room_type
        })

      this.add()
    },
    methods: {

      change_status (id,status_id,color) {
        this.items.forEach((o, i) => {

          if(o.id===id){
            this.items[i].status = status_id;
            this.items[i].status_color = color;
            this.items[i].status_show = false;
            return;
          }
        });

      },
      add () {
        let booking = {
          id: this.items_count++,
          status_show: null,
          status_color: null,
          status: null,
          accommodation: null,
          number_of_rooms: 0,
          room_type: null,
          check_in_date: null,
          nts: 0,
          price: 0,
          cost: 0
        }
        this.items.push(booking)
      },

      getPrice(id){
        let item = this.items.filter(i => {
          return i.id==id
        }).pop();
console.log(item);

        if(item.accommodation && item.number_of_rooms&&item.room_type&&item.check_in_date&&item.nts){
          axios.post('/api/hotels/store',item)
            .then(response => {

                item.cost = response.data.cost;
                item.price = response.data.price;
            })
        }
      },
      remove(id){
        this.items = this.items.filter(i=>i.id != id)
      }

    }

  }
</script>
<style>
    .status {
        margin: 0 auto;
        border-radius: 10px;
        width: 50px;
        height: 50px;
        background-color: #ff0000;
        cursor: pointer
    }

    input[type=radio] {
        margin-left: 20px;
        margin-top: 20px;
    }
</style>