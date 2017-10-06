
<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'JdBillForm',

    /**
    * Component's local state
    */
    data() {
      return {
        bill: {
          id: 0,
          flat_id: 0,
          monthly_bill_notification_id: 0,
          area: 0,
          cars: 0,
          water: 0,
          electricity: 0,
          total: 0,
          flat: {},
          feeStandard: {},
          feeDetail: {},
          is_paid: true,
        },
      }
    },

    /**
    * Fetch op when component is first mounted
    */
    mounted() {
      this.fetch()
    },

    /**
    * Also fetch op every time route changes
    */
    watch: {
      $route: 'fetch',
    },

    /**
    * Determines based on the presence of
    * op id if the current actions
    * is editing instead of creating.
    */
    computed: {
      isEditing() {
        return this.bill.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.bill.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill bill name'] })
          return false
        }
        if (this.bill.number === '') {
          this.setMessage({ type: 'error', message: ['Please fill bill name'] })
          return false
        }
        return true
      },
      currentPage() {
        return parseInt(this.$route.query.page, 10)
      },
      date() {
        return this.$route.query.date || ''
      },
      building() {
        return this.$route.query.building || 0
      },
      keyword() {
        return this.$route.query.keyword || ''
      },
    },

    methods: {
      ...mapActions(['setFetching', 'resetMessages', 'setMessage']),

      /**
      * If there's an ID in the route params
      * then use it to fetch the op
      * from the server
      */
      fetch() {
        this.$refs.firstInput.focus()

        const id = this.$route.params.id
        /**
        * This same component is used for create
        * and update so we have to check if
        * ID is not undefined.
        */
        if (id !== undefined) {
          /**
          * Fetch the op from the server
          */
          this.setFetching({ fetching: true })
          this.$http.get(`dct/bills/${id}`).then((res) => {
            const { id: _id, flat_id, monthly_bill_notification_id, total, flat, feeStandard, area, cars, water,
             electricity, is_paid } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.bill.id = _id
            this.bill.flat_id = flat_id
            this.bill.monthly_bill_notification_id = monthly_bill_notification_id
            this.bill.total = total
            this.bill.flat = flat
            this.bill.feeStandard = feeStandard
            this.bill.is_paid = is_paid
            this.bill.area = area
            this.bill.cars = cars
            this.bill.water = water
            this.bill.electricity = electricity

            /**
             * Calculating
             */
            this.bill.feeDetail = {
              property: Math.round(flat.area * feeStandard.property_management_fee * 100) / 100,
              cars: cars * 0,
              water: Math.round(water * feeStandard.water_fee * 100) / 100,
              electricity: Math.round(electricity * feeStandard.electricity_fee * 100) / 100,
            }
            this.setFetching({ fetching: false })
          })
        }
      },
      submit() {
        /**
        * Pre-conditions are met
        */
        if (this.isValid) {
          /**
          * Shows the global spinner
          */
          this.setFetching({ fetching: true })

          if (this.isEditing) {
            this.update()
          } else {
            this.save()
          }
        }
      },
      save() {
        this.$http.post('dct/bills', this.bill).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('bill.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Building was created' })

          /**
          * Resets component's state
          */
          // this.reset()
          this.$router.push({
            name: 'dctManager.bills.index',
            query: { page: this.currentPage, date: this.date, building: this.building, keyword: this.keyword }
          })
        })
      },
      update() {
        this.$http.put(`dct/bills/${this.bill.id}`, this.bill).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('bill.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'Building was updated' })
          this.$router.push({
            name: 'dctManager.bills.index',
            query: { page: this.currentPage, date: this.date, building: this.building, keyword: this.keyword }
          })
        })
      },
      reset() {
        this.bill.id = 0
        this.bill.is_paid = false
      },
      changed() {
        this.bill.feeDetail.water = Math.round(this.bill.water * this.bill.feeStandard.water_fee * 100) / 100
        this.bill.feeDetail.electricity = Math.round(this.bill.electricity * this.bill.feeStandard.electricity_fee * 100) / 100
        this.bill.total = Math.round((this.bill.feeDetail.property + this.bill.feeDetail.cars + this.bill.feeDetail.water + this.bill.feeDetail.electricity) * 100) / 100
      },
      hide() {
        this.$router.push({
          name: 'dctManager.bills.index',
          query: { page: this.currentPage, date: this.date, building: this.building, keyword: this.keyword }
        })
      },
    },
  }
</script>

<template>
  <!-- <div class="col-xs-12"> -->
    <form @submit.prevent="submit" class="well">
      <div class="form-group">
        <div class="row">
          <div style="text-align:center">
            <strong>费用合计（元）</strong>   ￥{{ bill.total }}
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>房屋大小</strong>
              </div>
              <div class="col-xs-5" style="padding-left:28px;">
                {{ bill.flat.area }} ㎡
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>物业管理费</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeDetail.property }} ￥
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>收费标准</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeStandard.property_management_fee }} 元/㎡
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>车辆数量</strong>
              </div>
              <div class="col-xs-5" style="padding-left:28px;">
                {{ bill.cars }}
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>停车费</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeDetail.cars }} ￥
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>收费标准</strong>
              </div>
              <div class="col-xs-5">
                100 元/辆
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>用水量（吨）</strong>
              </div>
              <div v-if="bill.is_paid" class="col-xs-5" style="padding-left:28px;">
                {{ bill.water }} 
              </div>
              <div v-else class="col-xs-5">
                <input type="number" min="0" step="0.01" class="form-control" v-model="bill.water" @change="changed" />
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>水费</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeDetail.water }} ￥
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>收费标准</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeStandard.water_fee }} 元/㎡
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>电费（度）</strong>
              </div>
              <div v-if="bill.is_paid" class="col-xs-5" style="padding-left:28px;">
                {{ bill.electricity }}
              </div>
              <div v-else class="col-xs-5">
                <input type="number" min="0" step="0.01" class="form-control" v-model="bill.electricity" @change="changed" />
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>电费</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeDetail.electricity }} ￥
              </div>
            </div>
          </div>
          <div class="col-sm-4 col-xs-12">
            <div class="row">
              <div class="col-xs-7" style="text-align:right">
                <strong>收费标准</strong>
              </div>
              <div class="col-xs-5">
                {{ bill.feeStandard.electricity_fee }} 元/度
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-xs-12">
            <a class="btn btn-default pull-left" @click="hide">返回</a>
            <button ref="firstInput" class="btn btn-primary pull-right" type="submit">保存</button>
          </div>
        </div>
      </div>
    </form>
  <!-- </div> -->
</template>
