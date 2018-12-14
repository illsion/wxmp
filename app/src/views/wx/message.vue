<template>
  <div>
    <mu-container>
      <mu-paper :z-depth="1" class="app-paper">
        <mu-row class="action-filter">
          <mu-col span="12">
            <mu-select v-model="queryData.type">
              <mu-option label="请选择类型" :value="null" />
              <mu-option v-for="(option,index) in typeIndex" :key="index" :label="option" :value="index" />
            </mu-select>
            <mu-button small color="info" @click="fetchData">
              搜索
            </mu-button>
            <mu-button color="success" small @click="openDialog({})">
              添加消息
            </mu-button>
          </mu-col>
        </mu-row>
        <mu-divider />
        <mu-data-table stripe :columns="columns" :data="list">
          <template slot-scope="scope">
            <td class="is-center">
              {{ scope.row.keywords }}
            </td>
            <td class="is-center">
              {{ scope.row.mp_message.title }}
            </td>
            <td class="is-center">
              {{ scope.row.typeText }}
            </td>
            <td class="is-center">
              {{ scope.row.statusText }}
            </td>
            <td class="is-center">
              <mu-button flat color="secondary" small @click="openDialog(scope.row)">
                编辑
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
      <mu-flex class="pagination-filter" justify-content="center">
        <mu-pagination raised :total="pageData.count" :page-size="pageData.limit" :current.sync="pageData.page" @change="fetchData" />
      </mu-flex>
    </mu-container>
    <update-message :edit-id="editId" :open.sync="open" :type-index="typeIndex" :status-index="statusIndex" :fields="fields" @fetch-data="fetchData" />
    <show-message :open.sync="info.open" :info="info.item" :fields="fields" />
  </div>
</template>

<script>
import { getRuleList, deleteRule } from '@/api/wx'
import UpdateMessage from './components/UpdateMessage'
import ShowMessage from './components/ShowMessage'
import { messageForm as defaultForm } from './components/messageForm'

export default {
  components: {
    UpdateMessage,
    ShowMessage
  },
  data() {
    return {
      queryData: {
        type: null
      },
      form: Object.assign({}, defaultForm),
      typeIndex: {},
      statusIndex: {},
      pageData: {
        limit: 10,
        count: 0,
        page: 1
      },
      columns: [
        { title: '关键字', name: 'username', align: 'center' },
        { title: '标题', name: 'mp_message.title', align: 'center' },
        { title: '类型', name: 'typeText', align: 'center' },
        { title: '状态', name: 'statusText', align: 'center' },
        { title: '操作', align: 'center', width: 500 }
      ],
      list: [],
      fields: {},
      open: false,
      editId: null,
      info: {
        open: false,
        item: Object.assign({}, defaultForm)
      }
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const queryData = {
        page: this.pageData.page,
        ...this.queryData
      }
      getRuleList(queryData).then(response => {
        this.list = response.items
        this.typeIndex = response.typeIndex
        this.statusIndex = response.statusIndex
        this.pageData = response.pageData
        this.fields = response.fields
      })
    },
    openDialog(item) {
      if (Object.keys(item).length === 0) {
        this.editId = null
      } else {
        this.editId = item.id
      }
      this.open = true
    },
    deleteItem(id) {
      this.$confirm('确定要删除？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (result) {
          deleteRule({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    },
    showInfo(item) {
      this.info.open = true
      this.info.item = Object.assign({}, item)
    }
  }
}
</script>
