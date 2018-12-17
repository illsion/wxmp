<template>
  <mu-dialog title="查看" width="800" scrollable :open.sync="open">
    <mu-list textline="two-line">
      <mu-list-item button>
        <mu-list-item-content>
          <mu-list-item-title>关键字</mu-list-item-title>
          <mu-list-item-sub-title>{{ info.keywords }}</mu-list-item-sub-title>
        </mu-list-item-content>
      </mu-list-item>
      <mu-list-item button>
        <mu-list-item-content>
          <mu-list-item-title>类型</mu-list-item-title>
          <mu-list-item-sub-title>{{ info.typeText }}</mu-list-item-sub-title>
        </mu-list-item-content>
      </mu-list-item>
      <mu-list-item button>
        <mu-list-item-content>
          <mu-list-item-title>状态</mu-list-item-title>
          <mu-list-item-sub-title>{{ info.statusText }}</mu-list-item-sub-title>
        </mu-list-item-content>
      </mu-list-item>
      <mu-list-item button>
        <mu-list-item-content>
          <mu-list-item-title>标题</mu-list-item-title>
          <mu-list-item-sub-title>{{ info.mp_message.title }}</mu-list-item-sub-title>
        </mu-list-item-content>
      </mu-list-item>
      <mu-list-item v-show="fieldsRule.description" button>
        <mu-list-item-content>
          <mu-list-item-title>描述</mu-list-item-title>
          <mu-list-item-sub-title>{{ info.mp_message.description }}</mu-list-item-sub-title>
        </mu-list-item-content>
      </mu-list-item>
      <mu-list-item v-show="fieldsRule.url" button>
        <mu-list-item-content>
          <mu-list-item-title>链接</mu-list-item-title>
          <mu-list-item-sub-title>{{ info.mp_message.url }}</mu-list-item-sub-title>
        </mu-list-item-content>
      </mu-list-item>
    </mu-list>
    <div v-show="fieldsRule.content" class="app-list">
      <div class="title">
        内容
      </div>
      <div class="mu-item-sub-title">
        {{ info.mp_message.content }}
      </div>
    </div>
    <div v-show="fieldsRule.media_url" class="app-list">
      <div class="title">
        图片
      </div>
      <img :src="info.mp_message.full_media_url" width="80" height="80">
    </div>
    <mu-button slot="actions" flat @click="closeDialog">
      关闭
    </mu-button>
  </mu-dialog>
</template>

<script>

import { fieldsForm } from './formData'

export default {
  name: 'ShowMessage',
  props: {
    info: {
      type: Object,
      required: true
    },
    open: {
      type: Boolean,
      default: false
    },
    fields: {
      type: Object,
      required: true
    }
  },
  computed: {
    fieldsRule() {
      if (this.fields[this.info.type]) {
        return Object.assign({}, fieldsForm, this.fields[this.info.type])
      } else {
        return Object.assign({}, fieldsForm)
      }
    }
  },
  methods: {
    closeDialog() {
      this.$emit('update:open', false)
    }
  }
}
</script>

<style scoped>
    .app-list {
        padding-right: 16px;
        padding-left: 16px;
        color: rgba(0,0,0,.87);
        margin-bottom: 20px;
    }
</style>
