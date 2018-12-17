<template>
  <mu-dialog full-width :esc-press-close="false" :overlay-close="false" :title="validateForm.id ? '编辑' : '添加'" width="800" scrollable :open.sync="open">
    <div>
      <mu-tabs inverse :value.sync="validateForm.type" color="secondary" text-color="rgba(0, 0, 0, .54)">
        <mu-tab v-for="(option, index) in typeIndex" :key="index" :value="index">
          {{ option }}
        </mu-tab>
      </mu-tabs>
      <div>
        <mu-form ref="form" :model="validateForm">
          <mu-form-item label-float label="菜单名称" prop="name" :rules="rules.name">
            <mu-text-field v-model="validateForm.name" prop="name" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.key" label="关键字">
            <mu-text-field v-model="validateForm.content.key" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.appid" label="appid">
            <mu-text-field v-model="validateForm.content.appid" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.url" label="url">
            <mu-text-field v-model="validateForm.content.url" />
          </mu-form-item>
          <mu-form-item v-show="fieldsRule.pagepath" label="pagepath">
            <mu-text-field v-model="validateForm.content.pagepath" />
          </mu-form-item>
        </mu-form>
      </div>
    </div>

    <mu-button slot="actions" v-loading="loading" flat color="primary" :disabled="loading" @click="updateForm">
      确定
    </mu-button>
    <mu-button slot="actions" flat @click="closeDialog">
      取消
    </mu-button>
  </mu-dialog>
</template>

<script>
import { getMenuItem, updateMenu } from '@/api/wx'
import { menuType as typeIndex, menuFieldsForm as fieldsForm } from './formData'

const defaultForm = {
  id: null,
  name: '',
  type: 'click',
  content: {
    key: '',
    appid: '',
    url: '',
    pagepath: ''
  }
}

export default {
  name: 'UpdateMenu',
  props: {
    open: {
      type: Boolean,
      default: false
    },
    editId: {
      type: Number,
      default: null
    },
    parent: {
      type: Object,
      default() {
        return {
          id: null,
          name: null
        }
      }
    }
  },
  data() {
    return {
      rules: {
        name: [
          { validate: (val) => !!val, message: '不能为空' }
        ]
      },
      loading: false,
      validateForm: Object.assign({}, defaultForm),
      typeIndex: typeIndex
    }
  },
  computed: {
    fieldsRule() {
      return Object.assign({}, fieldsForm[this.validateForm.type])
    }
  },
  watch: {
    editId(newVal) {
      this.fetchData(newVal)
    }
  },
  methods: {
    fetchData(id) {
      if (id) {
        this.loading = true
        getMenuItem({
          id: id
        }).then(response => {
          response.type = response.type || 'click'
          if (response.content) {
            this.validateForm = Object.assign({}, defaultForm, response)
          } else {
            this.validateForm = Object.assign({}, response, {
              content: defaultForm.content
            })
          }
          this.loading = false
        }).catch(() => {
          this.loading = false
        })
      } else {
        this.validateForm = Object.assign({}, defaultForm)
      }
    },
    closeDialog() {
      this.loading = false
      this.$emit('update:open', false)
    },
    updateForm() {
      this.$refs.form.validate().then((result) => {
        if (result) {
          this.loading = true
          let form
          if (this.validateForm.id) {
            form = Object.assign({}, this.validateForm)
          } else {
            // 新增添加父菜单
            form = Object.assign({}, this.validateForm, {
              parent_id: this.parent.id
            })
          }
          updateMenu(form).then(response => {
            this.loading = false
            this.$emit('after-action')
            this.closeDialog()
          }).catch(() => {
            this.loading = false
          })
        }
      })
    }
  }
}
</script>

