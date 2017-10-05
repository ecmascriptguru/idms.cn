
<script>
  import { mapActions, mapGetters } from 'vuex'
  import CcSpinner from '../general/spinner'
  import { version } from '../../config'

  export default {
    components: {
      CcSpinner,
    },
    computed: {
      /**
      * See src/app/auth/vuex/getters.js
      */
      ...mapGetters(['currentUser', 'isLogged']),
      version() {
        return version
      },
    },
    watch: {
      isLogged(value) { // isLogged changes when the token changes
        if (value === false) {
          this.$router.push({ name: 'auth.singin' })
        }
      },
    },
    methods: {
      /**
      * Makes logout() action available withint this component
      * See /src/app/auth/vuex/actions.js
      */
      ...mapActions(['logout']),
      /* eslint-disable no-undef */
      navigate(name) {
        switch (name) {
          case 'codecasts':
            window.location = 'https://codecasts.com.br/'
            break;
          case 'logout':
            this.logout()
            break;
          default:
            this.$router.push({ name })
            break;
        }
      },
    },
  }
</script>

<template>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height:auto;">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../../assets/img/avatar1.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ currentUser.name }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul v-if="(currentUser.role || {}).name === 'Admin'" class="sidebar-menu">
        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-cog"></i> <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active" :to="{ path: '/admin/roles', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> Roles</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/admin/houseTypes', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> House Types</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/admin/customFeeTypes', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> Custom Fee Types</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/admin/parkingLots', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> ParkingLots</a>
            </router-link>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-wrench"></i> <span>Operations</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active" :to="{ path: '/admin/ops', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> Operating Companies</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/admin/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> Users</a>
            </router-link>
            <!-- <router-link tag="li" active-class="active" :to="{ path: '/products', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> Products</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/categories', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> Categories</a>
            </router-link> -->
          </ul>
        </li>
      </ul>

      <ul v-else-if="(currentUser.role || {}).id === 2" class="sidebar-menu">
        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>物业公司设置</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/op/ppCompanies', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 物业公司</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/op/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 账户设置</a>
            </router-link>
          </ul>
        </li>

        <router-link tag="li" active-class="active" :to="{ path: '/op/shop' }" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-shopping-cart"></i> <span>运营商设置</span>
          </a>
        </router-link>

        <router-link tag="li" active-class="active" :to="{ path: '/op/retailer', query: { page: 1} }" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-book"></i> <span>商铺入住审核</span>
          </a>
        </router-link>

        <li class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-share"></i> <span>广告设置</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active" :to="{ path: '/op/appAdvs', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> APP广告</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/op/deviceAdvs', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 设备广告管理</a>
            </router-link>
          </ul>
        </li>

        <router-link tag="li" active-class="active" :to="{ path: '/op/devices', query: { page: 1} }" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-wrench"></i> <span>设备监控</span>
          </a>
        </router-link>

        <router-link tag="li" active-class="active" :to="{ path: '/op/parameters', query: { page: 1} }" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-blackboard"></i> <span>设备参数</span>
          </a>
        </router-link>
      </ul>

      <ul v-else-if="(currentUser.role || {}).id === 3" class="sidebar-menu">
        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>小区设置</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/ppc/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 小区管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/ppc/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 账户设置</a>
            </router-link>
          </ul>
        </li>

        <router-link tag="li" active-class="active" :to="{ path: '/ppc/feeStandards', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>费用标准</span>
          </a>
        </router-link>

        <router-link tag="li" active-class="active" :to="{ path: '/ppc/payments', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>缴费查询</span>
          </a>
        </router-link>

        <router-link tag="li" active-class="active" :to="{ path: '/ppc/parkings', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>小区停车场</span>
          </a>
        </router-link>

        <router-link tag="li" active-class="active" :to="{ path: '/ppc/devices', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>设备监控</span>
          </a>
        </router-link>
      </ul>

      <ul v-else-if="(currentUser.role || {}).id === 4" class="sidebar-menu">
        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>档案管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 楼栋档案</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 房屋档案</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 用户导入</a>
            </router-link>
          </ul>
        </li>

        <router-link tag="li" active-class="active" :to="{ path: '/dct/costs', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>房屋认证</span>
          </a>
        </router-link>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>物业费用</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 催缴管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 账单管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 缴费</a>
            </router-link>
          </ul>
        </li>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>物业管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 公告管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 房屋报修</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 投诉建议</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 系统物业</a>
            </router-link>
          </ul>
        </li>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>门禁系统</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 门禁设备</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 设备监控</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 门禁卡管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 指纹管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 开门记录</a>
            </router-link>
          </ul>
        </li>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>梯控系统</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 业主卡管理</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 电梯管理</a>
            </router-link>
          </ul>
        </li>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>停车场系统</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 停车场信息</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 车位审核</a>
            </router-link>
            <router-link tag="li" active-class="active" :to="{ path: '/dct/users', query: { page: 1 } }">
              <a href="#"><i class="fa fa-circle-o"></i> 月租车缴费</a>
            </router-link>
          </ul>
        </li>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>社区互动</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 社区话题</a>
            </router-link>
          </ul>
        </li>

        <router-link tag="li" active-class="active" :to="{ path: '/dct/payments', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>商家入驻</span>
          </a>
        </router-link>

        <router-link tag="li" active-class="active" :to="{ path: '/dct/parkings', query: { page: 1} }">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>住户管理</span>
          </a>
        </router-link>

        <li active-class="active" class="treeview">
          <a href="#">
            <i class="glyphicon glyphicon-home"></i> <span>员工管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" active-class="active"  :to="{ path: '/dct/districts', query: { page: 1} }">
              <a href="#"><i class="fa fa-circle-o"></i> 员工信息</a>
            </router-link>
          </ul>
        </li>
      </ul>

      <ul v-else class="sidebar-menu">
        <!-- <li class="header">MAIN NAVIGATION</li> -->
        <li class="treeview">
          <a href="#">
            <i class="fa fa-shopping-bag"></i> <span>Modules</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" :to="{ path: 'products' }">
              <a href="#"><i class="fa fa-circle-o"></i> Products</a>
            </router-link>
            <router-link tag="li" :to="{ path: 'categories' }">
              <a href="#"><i class="fa fa-circle-o"></i> Categories</a>
            </router-link>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <router-link tag="li" :to="{ path: '/' }">
              <a href="#"><i class="fa fa-circle-o"></i> Dashboard</a>
            </router-link>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/widgets.html">
            <i class="fa fa-th"></i> <span>Widgets</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Charts</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
            <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
            <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
            <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>UI Elements</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
            <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
            <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
            <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
            <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
            <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
            <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
            <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
            <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>
        <li>
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
            <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
            <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
            <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
            <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
            <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
            <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
            <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li>
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          </ul>
        </li>
        <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
</template>

<style scoped>
</style>
