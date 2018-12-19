<template>
  <mu-container>
    <mu-paper :z-depth="1" class="app-paper">
      <mu-row class="action-filter">
        <mu-col span="12">
          <mu-button color="info" small @click="synchronize">
            同步菜单
          </mu-button>
          <mu-button color="secondary" small @click="updateDialog(null)">
            添加菜单
          </mu-button>
          <mu-button color="success" small @click="releaseMenus">
            发布菜单
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
            <mu-button flat color="secondary" small @click="updateDialog(scope.row.id)">
              编辑
            </mu-button>
            <mu-button v-if="parseInt(scope.row.parent_id) === 0" flat color="secondary" small @click="updateDialog(null, scope.row)">
              添加子菜单
            </mu-button>
            <mu-button flat color="success" small @click="showInfo(scope.row)">
              查看
            </mu-button>
            <mu-button flat small @click="deleteItem(scope.row.id)">
              删除
            </mu-button>
          </td>
        </template>
      </mu-data-table>
    </mu-paper>
    <mu-dialog title="查看" width="800" scrollable :open.sync="info.open">
      <mu-list textline="two-line">
        <mu-list-item v-if="info.item.type" button>
          <mu-list-item-content>
            <mu-list-item-title>类型</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.type | formatType }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button>
          <mu-list-item-content>
            <mu-list-item-title>菜单名称</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.name }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item v-for="(item, key) in info.item.content" :key="key" button>
          <mu-list-item-content>
            <mu-list-item-title>{{ key | formatContent }}</mu-list-item-title>
            <mu-list-item-sub-title>{{ item }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
      </mu-list>
      <mu-button slot="actions" flat @click="info.open = false">
        关闭
      </mu-button>
    </mu-dialog>
    <update-menus :open.sync="update.open" :edit-id="update.id" :parent="update.parent" @after-action="fetchData" />
  </mu-container>
</template>

<script>
import { synchronizeMenu, getMenuList, releaseMenus, deleteMenu } from '../../api/wx'
import { menuType as typeIndex, menuContent as label } from './components/formData'
import UpdateMenus from './components/UpdateMenu'
import check from './check'

const updateDefult = {
  open: false,
  id: null,
  parent: {
    id: null,
    name: null
  }
}
export default {
  components: {
    UpdateMenus
  },
  filters: {
    formatContent(val) {
      return label[val] || val
    },
    formatType(val) {
      return typeIndex[val] || val
    }
  },
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
      },
      update: Object.assign({}, updateDefult)
    }
  },
  computed: {
    items() {
      const arr = []
      for (const val of this.list) {
        const children = val.children
        delete val.children
        val.newName = val.name
        arr.push(val)
        if (children.length > 0) {
          for (const item of children) {
            item.newName = '|---- ' + item.name
            arr.push(item)
          }
        }
      }
      return arr
    }
  },
  created() {
    check()
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
      })
    },
    updateDialog(id, parent = null) {
      this.update.id = id
      if (parent !== null) {
        this.update.parent = {
          id: parent.id,
          name: parent.name
        }
      } else {
        this.update.parent = {
          id: 0,
          name: null
        }
      }
      this.update.open = true
    },
    deleteItem(id) {
      this.$confirm('确定要删除？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (result) {
          deleteMenu({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    },
    releaseMenus() {
      releaseMenus().then(response => {
        this.$toast.success('发布成功')
      })
    }
  }
}
</script>

<style scoped>

</style>
