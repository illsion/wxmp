<template>
  <div>
    <mu-container>
      <mu-paper :z-depth="1" class="app-paper">
        <mu-row class="action-filter">
          <mu-col span="12">
            <mu-button color="success" small @click="openDialog({})">
              添加公众号
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
              {{ scope.row.typeText }}
            </td>
            <td class="is-center">
              <mu-button flat color="primary" small @click="pick(scope.row)">
                切换公众号
              </mu-button>
              <mu-button flat color="secondary" small @click="openDialog(scope.row)">
                编辑
              </mu-button>
              <mu-button flat color="success" small @click="showInfo(scope.row)">
                接入信息
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
        <mu-form-item label-float label="公众号名称" prop="name" :rules="rules.name">
          <mu-text-field v-model="validateForm.name" prop="name" />
        </mu-form-item>
        <mu-form-item label-float label="公众号原始ID" prop="origin_id" :rules="rules.origin_id">
          <mu-text-field v-model="validateForm.origin_id" prop="origin_id" />
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
        <mu-form-item prop="type" label="公众号类型" :rules="rules.type">
          <mu-radio v-for="(typeItem, key) in typeIndex" :key="key" v-model.number="validateForm.type" :value="parseInt(key)" :label="typeItem" />
        </mu-form-item>
        <mu-form-item label-float prop="description" label="描述">
          <mu-text-field v-model="validateForm.description" multi-line :rows="1" :rows-max="6" :max-length="150" />
        </mu-form-item>
      </mu-form>
      <mu-button slot="actions" v-loading="loading" flat color="primary" :disabled="loading" @click="updateForm">
        确定
      </mu-button>
      <mu-button slot="actions" flat @click="closeDialog">
        取消
      </mu-button>
    </mu-dialog>
    <mu-dialog title="接入信息" scrollable :open.sync="info.open">
      <mu-list textline="two-line">
        <mu-list-item button @click="doCopy(info.item.url)">
          <mu-list-item-content>
            <mu-list-item-title>服务器地址</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.url }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
        <mu-list-item button @click="doCopy(info.item.token)">
          <mu-list-item-content>
            <mu-list-item-title>Token</mu-list-item-title>
            <mu-list-item-sub-title>{{ info.item.token }}</mu-list-item-sub-title>
          </mu-list-item-content>
        </mu-list-item>
      </mu-list>
      <mu-button slot="actions" flat @click="info.open = false">
        取消
      </mu-button>
    </mu-dialog>
  </div>
</template>

<script>
import { getList, update, deleteMp } from '@/api/mp'

const defaultForm = {
  id: null,
  name: '',
  appid: '',
  secret: '',
  token: '',
  origin_id: '',
  type: '',
  description: '',
  qrcode: ''
}
export default {
  name: 'Wx',
  data() {
    return {
      validateForm: Object.assign({}, defaultForm),
      pageData: {
        limit: 10,
        count: 0,
        page: 1
      },
      columns: [
        { title: '公众号名称', name: 'name', align: 'center' },
        { title: '公众号类型', name: 'type_text', align: 'center' },
        { title: '操作', align: 'center', width: 500 }
      ],
      list: [],
      typeIndex: null,
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
        ],
        origin_id: [
          { validate: (val) => !!val, message: '不能为空' }
        ],
        type: [
          { validate: (val) => !!val, message: '不能为空' }
        ]
      },
      open: false,
      loading: false,
      info: {
        open: false,
        item: {}
      }
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
        this.typeIndex = response.typeIndex
      })
    },
    closeDialog() {
      this.open = false
    },
    openDialog(item) {
      this.open = true
      if (Object.keys(item).length === 0) {
        this.validateForm = Object.assign({}, defaultForm)
      } else {
        this.validateForm = Object.assign({}, item)
      }
    },
    createData() {
      this.open = true
    },
    updateForm() {
      this.$refs.form.validate().then((result) => {
        if (result) {
          this.loading = true
          update(this.validateForm).then(response => {
            if (this.$store.getters.pickType.key === 'mp' && this.$store.getters.pickInfo.id === this.validateForm.id) {
              this.$store.dispatch('setPick', {
                info: {
                  name: this.validateForm.name,
                  id: this.validateForm.id
                },
                type: 'mp'
              })
            }
            this.loading = false
            this.closeDialog()
            this.fetchData()
          }).catch(() => {
            this.loading = false
          })
        }
      })
    },
    deleteItem(id) {
      this.$confirm('确定要删除？', '提示', {
        type: 'warning'
      }).then(({ result }) => {
        if (this.$store.getters.pickType.key === 'mp' && this.$store.getters.pickInfo.id === id) {
          this.$store.dispatch('removePick')
        }
        if (result) {
          deleteMp({ id: id }).then(response => {
            this.fetchData()
          })
        }
      })
    },
    // 切换公众号
    pick(item) {
      this.$store.dispatch('setPick', {
        info: {
          name: item.name,
          id: item.id
        },
        type: 'mp'
      }).then(response => {
        this.$toast.success('切换成功！')
      })
    },
    showInfo(item) {
      this.info.open = true
      this.info.item = Object.assign({}, item)
    },
    doCopy(text) {
      this.$copyText(text).then(e => {
        this.$toast.success('复制成功')
      }, e => {
        this.$toast.error('复制失败')
      })
    }
  }
}
</script>

<style scoped>

</style>
