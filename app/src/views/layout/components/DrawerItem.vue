<template>
  <div>
    <mu-list toggle-nested>
      <div v-for="item in routers" :key="item.path">
        <template v-if="item.onlyChild">
          <mu-list-item button :to="item.children[0].path">
            <mu-list-item-action v-if="item.children[0].meta.icon">
              <mu-icon :value="item.children[0].meta.icon" />
            </mu-list-item-action>
            <mu-list-item-title>{{ item.children[0].meta.title }}</mu-list-item-title>
          </mu-list-item>
        </template>
        <template v-else-if="!item.hidden&&item.children">
          <mu-list-item
            button
            nested
            :open="open === item.path"
            @toggle-nested="open = arguments[0] ? item.path : ''"
          >
            <mu-list-item-action v-if="item.meta.icon">
              <mu-icon :value="item.meta.icon" />
            </mu-list-item-action>
            <mu-list-item-title>{{ item.meta.title }}</mu-list-item-title>
            <mu-list-item-action>
              <mu-icon class="toggle-icon" size="24" value="keyboard_arrow_down" />
            </mu-list-item-action>
            <template v-for="child in item.children">
              <mu-list-item v-if="!child.hidden" :key="child.path" slot="nested" button :to="child.path">
                <mu-list-item-title>{{ child.meta.title }}</mu-list-item-title>
              </mu-list-item>
            </template>
          </mu-list-item>
        </template>
      </div>
    </mu-list>
  </div>
</template>

<script>
import path from 'path'
export default {
  name: 'DrawerItem',
  data() {
    return {
      open: ''
    }
  },
  computed: {
    routers() {
      const routers = this.$store.getters.routers
      return routers.filter(item => {
        if (item.hidden) {
          return false
        }
        item.path = this.resolvePath('', item.path)

        const child = item.children.filter(value => {
          if (value.hidden) {
            return false
          } else {
            value.path = this.resolvePath(item.path, value.path)
            if (value.path === this.$route.path) {
              this.setOpen(item.path)
            }
            return value
          }
        })
        if (child.length === 1) {
          item.onlyChild = true
          item.children = child
        } else if (child.length === 0) {
          return false
        } else {
          item.onlyChild = false
          item.children = child
        }
        return item
      })
    }
  },
  methods: {
    resolvePath(basePath, routePath) {
      return path.resolve(basePath, routePath)
    },
    setOpen(value) {
      this.open = value
    }
  }
}
</script>

<style scoped>
  .mu-list li {
    list-style: none;
  }
  .mu-list /deep/ .mu-list .mu-item-title {
    font-size: 14px;
  }
  .mu-list /deep/ .router-link-exact-active .mu-item {
    color: #2196f3;
  }
</style>
