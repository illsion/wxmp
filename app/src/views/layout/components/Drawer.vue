<template>
  <div>
    <mu-drawer :open.sync="open" :docked="docked" :right="position === 'right'" :z-depth="1">
      <mu-list>
        <mu-list-item :to="'/config/wx'">
          <mu-list-item-content>
            <mu-list-item-title class="app-title">
              微信管理系统
            </mu-list-item-title>
            <mu-list-item-sub-title v-if="pickInfo !== false" class="app-sub-title">
              {{ pickInfo.type }}：{{ pickInfo.name }}
            </mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
      </mu-list>
      <mu-divider />
      <drawer-item />
    </mu-drawer>
  </div>
</template>

<script>
import DrawerItem from './DrawerItem'
export default {
  name: 'Drawer',
  components: {
    DrawerItem
  },
  data() {
    return {
      docked: true,
      open: true,
      position: 'left'
    }
  },
  computed: {
    pickInfo() {
      const info = this.$store.getters.pickInfo
      const type = this.$store.getters.pickType
      if (Object.keys(info).length === 0 || !type) {
        return false
      }
      return {
        name: info.name,
        type: type.value
      }
    }
  }
}
</script>

<style scoped>
    .app-title {
        color: #555;
        font-size: 22px;
        font-weight: 500;
    }
  .app-sub-title {
    font-size: 12px;
  }
</style>
