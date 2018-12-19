<template>
  <div>
    <mu-container>
      <mu-paper :z-depth="1" class="app-paper">
        <mu-row class="action-filter">
          <mu-col span="12">
            <mu-button color="success" small @click="openDialog({})">
              添加小程序
            </mu-button>
          </mu-col>
        </mu-row>
        <mu-divider />
        <mu-data-table stripe :columns="columns" :data="list">
          <template slot-scope="scope">
            <td class="is-center">
              {{ scope.row.name }}
            </td>
            <td class="is-center">
              {{ scope.row.appid }}
            </td>
            <td class="is-center">
              {{ scope.row.secret }}
            </td>
            <td class="is-center">
              {{ scope.row.token }}
            </td>
            <td class="is-center">
              <mu-button flat color="secondary" small @click="openDialog(scope.row)">
                编辑
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
    <mu-dialog :esc-press-close="false" :overlay-close="false" :title="validateForm.id ? '编辑' : '添加'" width="750" scrollable :open.sync="open">
      <mu-form ref="form" :model="validateForm">
        <mu-form-item label-float label="小程序名称" prop="name" :rules="rules.name">
          <mu-text-field v-model="validateForm.name" prop="name" />
        </mu-form-item>
        <mu-form-item label-float label="appid" prop="appid" :rules="rules.appid">
          <mu-text-field v-model="validateForm.appid" prop="appid" />
        </mu-form-item>
        <mu-form-item label-float label="app_secret" prop="secret" :rules="rules.secret">
          <mu-text-field v-model="validateForm.secret" prop="secret" />
        </mu-form-item>
        <mu-form-item label-float label="token" prop="token" :rules="rules.token">
          <mu-text-field v-model="validateForm.token" prop="token" />
        </mu-form-item>
      </mu-form>
      <mu-button slot="actions" v-loading="loading" flat color="primary" :disabled="loading" @click="updateForm">
        确定
      </mu-button>
      <mu-button slot="actions" flat @click="closeDialog">
        取消
      </mu-button>
    </mu-dialog>
  </div>
</template>

<script>
import { getList, update, deleteMini } from '@/api/mini'

const defaultForm = {
  id: null,
  name: '',
  appid: '',
  secret: '',
  token: ''
}
export default {
  data() {
    return {
      validateForm: Object.assign({}, defaultForm),
      loading: false,
      pageData: {
        limit: 10,
        count: 0,
        page: 1
      },
      columns: [
        { title: '小程序名称', name: 'name', align: 'center' },
        { title: 'appid', name: 'appid', align: 'center' },
        { title: 'app_secret', name: 'secret', align: 'center' },
        { title: 'token', name: 'token', align: 'center' },
        { title: '操作', align: 'center', width: 500 }
      ],
      list: [],
      rules: {
        name: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        appid: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        secret: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        token: [
          { validate: (val) => !!val, message: '不能为空' }
        ]
      },
      open: false
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    fetchData() {
      const queryData = {
        page: this.pageData.page
      }
      getList(queryData).then(response => {
        this.list = response.items
        this.pageData = response.pageData
      })
    },
    closeDialog() {
      this.open = false
    },
    openDialog(item) {
      if (Object.keys(item).length === 0) {
        this.validateForm = Object.assign({}, defaultForm)
      } else {
        this.validateForm = Object.assign({}, item)
      }
      this.open = true
    },
    deleteItem(id) {
      this.$confirm('确定要删除？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (result) {
          deleteMini({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    },
    updateForm() {
      this.$refs.form.validate().then((result) => {
        if (result) {
          this.loading = true
          update(this.validateForm).then(response => {
            this.loading = false
            this.closeDialog()
            this.fetchData()
          }).catch(() => {
            this.loading = false
          })
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
