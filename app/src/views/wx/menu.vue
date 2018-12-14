<template>
  <mu-container>
    <mu-paper :z-depth="1" class="app-paper">
      <mu-row class="action-filter">
        <mu-col span="12">
          <mu-button color="info" small @click="synchronize">
            同步菜单
          </mu-button>
        </mu-col>
      </mu-row>
      <mu-divider />
      <mu-data-table stripe :columns="columns" :data="items">
        <template slot-scope="scope">
          <td>
            {{ scope.row.newName }}
          </td>
          <td class="is-center">
            <mu-button flat color="secondary" small>
              编辑
            </mu-button>
            <mu-button flat color="success" small @click="showInfo(scope.row)">
              查看
            </mu-button>
            <mu-button flat small>
              删除
            </mu-button>
          </td>
        </template>
      </mu-data-table>
    </mu-paper>
    <mu-dialog title="查看" width="800" scrollable :open.sync="info.open">
      <mu-list textline="two-line">
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>菜单名称</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.name }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
      </mu-list>
      <mu-button slot="actions" flat @click="info.open = false">
        关闭
      </mu-button>
    </mu-dialog>
  </mu-container>
</template>

<script>
import { synchronizeMenu, getMenuList } from '../../api/wx'

export default {
  data() {
    return {
      list: [],
      columns: [
        { title: '菜单名称', name: 'newName', align: 'center' },
        { title: '操作', align: 'center', width: 500 }
      ],
      info: {
        open: false,
        item: []
      }
    }
  },
  computed: {
    items() {
      const arr = []
      for (const val of this.list) {
        const chilren = val.children
        delete val.children
        val.newName = val.name
        arr.push(val)
        if (chilren.length > 0) {
          for (const item of chilren) {
            item.newName = '|---- ' + item.name
            arr.push(item)
          }
        }
      }
      return arr
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      getMenuList().then(response => {
        this.list = response
      })
    },
    showInfo(row) {
      this.info = {
        open: true,
        item: row
      }
    },
    synchronize() {
      synchronizeMenu().then((response) => {
        this.$toast.success('同步成功')
        this.fetchData()
      }).catch(() => {
        this.$toast.error('同步失败')
      })
    }
  }
}
</script>

<style scoped>

</style>
