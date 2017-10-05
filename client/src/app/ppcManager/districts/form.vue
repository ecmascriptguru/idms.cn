
<script>
  import { mapActions, mapState } from 'vuex'

  export default {
    name: 'JdDistrictForm',

    /**
    * Component's local state
    */
    data() {
      return {
        district: {
          id: 0,
          name: '',
          short_name: '',
          province: '',
          city: '',
          contact: '',
          phone: '',
          parking_lot_id: '',
          address: '',
        },
      }
    },

    /**
    * Fetch op when component is first mounted
    */
    mounted() {
      this.fetchParkingLots()
      this.fetch()
      this.setParkingLotId()
    },

    updated() {
      this.setParkingLotId()
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
      ...mapState({
        parkingLots: state => state.Admin.ParkingLots.full_list
      }),
      isEditing() {
        return this.district.id > 0
      },
      isValid() {
        this.resetMessages()
        if (this.district.name === '') {
          this.setMessage({ type: 'error', message: ['Please fill district name'] })
          return false
        }
        if (this.district.short_name === '') {
          this.setMessage({ type: 'error', message: ['Please fill district short name'] })
          return false
        }
        if (this.district.contact === '') {
          this.setMessage({ type: 'error', message: ['Please fill district contact'] })
          return false
        }
        if (this.district.phone === '') {
          this.setMessage({ type: 'error', message: ['Please fill district phone'] })
          return false
        }
        if (this.district.address === '') {
          this.setMessage({ type: 'error', message: ['Please fill district address'] })
          return false
        }
        return true
      },
    },

    methods: {
      ...mapActions(['parkingLotsSetData', 'setFetching', 'resetMessages', 'setMessage']),

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
          this.$http.get(`pca/districts/${id}`).then((res) => {
            const { id: _id, name, province, city, short_name, contact, phone, address, parking_lot_id } = res.data.data // http://wesbos.com/destructuring-renaming/
            this.district.id = _id
            this.district.name = name
            this.district.province = province
            this.district.city = city
            this.district.short_name = short_name
            this.district.contact = contact
            this.district.phone = phone
            this.district.address = address
            this.district.parking_lot_id = parking_lot_id
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
      setParkingLotId() {
        $("#parking_lot_id").val(this.district.parking_lot_id)
      },
      fetchParkingLots() {
        if (!this.parkingLots.length) {
          this.setFetching({ fetching: true })
          this.$http.get('admin/parkingLots/full-list').then(({ data }) => {
            /**
            * Vuex action to set full list array in
            * the Vuex OperatingCompanies module
            */
            this.parkingLotsSetData({
              full_list: data.data,
            })
            this.setFetching({ fetching: false })
            this.setParkingLotId()
          })
        } else {
          this.setParkingLotId()
        }
      },
      save() {
        this.$http.post('pca/districts', 
          { 
            name: this.district.name,
            short_name: this.district.short_name,
            contact: this.district.contact,
            province: this.district.province,
            city: this.district.city,
            phone: this.district.phone,
            parking_lot_id: this.district.parking_lot_id,
            address: this.district.address,
          }).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('district.created')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'New operating District was created' })

          /**
          * Resets component's state
          */
          this.reset()
        })
      },
      update() {
        this.$http.put(`pca/districts/${this.district.id}`, this.district).then(() => {
          /**
          * This event will notify the world about
          * the op creation. In this case
          * the Op main component will intercept
          * the event and refresh the list.
          */
          this.$bus.$emit('district.updated')

          /**
          * Hides the global spinner
          */
          this.setFetching({ fetching: false })

          /**
          * Sets the global feedback message
          */
          this.setMessage({ type: 'success', message: 'District was updated' })
        })
      },
      reset() {
        this.district.id = 0
        this.district.name = ''
        this.district.short_name = ''
        this.province = '',
        this.city = '',
        this.district.contact = ''
        this.district.phone = ''
        this.district.address = ''
        this.parking_lot_id = ''
      },
    },
  }
</script>

<template>
  <form @submit.prevent="submit" class="well">
    <div class="form-group">
      <label for="name" class="control-label">社区名称</label>
      <input ref="firstInput" type="text" id="name" class="form-control" v-model="district.name">
    </div>
    <div class="form-group">
      <label for="short_name" class="control-label">社区简称</label>
      <input type="text" id="short_name" class="form-control" v-model="district.short_name">
    </div>
    <div class="form-group">
      <label for="province" class="control-label">省</label>
      <input type="text" id="province" class="form-control" v-model="district.province">
    </div>
    <div class="form-group">
      <label for="city" class="control-label">市</label>
      <input type="text" id="city" class="form-control" v-model="district.city">
    </div>
    <div class="form-group">
      <label for="contact" class="control-label">联系人</label>
      <input type="text" id="contact" class="form-control" v-model="district.contact">
    </div>
    <div class="form-group">
      <label for="phone" class="control-label">手机</label>
      <input type="text" id="phone" class="form-control" v-model="district.phone">
    </div>
    <div class="form-group">
      <label for="address" class="control-label">地址</label>
      <input type="text" id="address" class="form-control" v-model="district.address">
    </div>
    <div class="form-group">
      <label for="parking_lot_id" class="control-label">停车场</label>
      <select id="parking_lot_id" name="parking_lot_id" class="form-control" v-model="district.parking_lot_id">
        <option v-for="parkingLot in parkingLots" :value="parkingLot.id">
          {{ parkingLot.name }}
        </option>
      </select>
    </div>
    <button class="btn btn-primary" type="submit">保存</button>
  </form>
</template>
