<template>
  <div class="upload">
    <mu-button color="primary" small @click="addFile">
      添加图片
      <file-upload
        ref="editupload"
        v-model="files"
        :post-action="action"
        :accept="accept"
        :headers="headers"
        :data="{
          send: sendMedia
        }"
        @input-file="inputFile"
        @input-filter="inputFilter"
      />
    </mu-button>
  </div>
</template>

<script>
import VueUploadComponent from 'vue-upload-component'
import { getToken } from '@/utils/cookie'

export default {
  name: 'EditImage',
  components: {
    FileUpload: VueUploadComponent
  },
  props: {
    img: {
      type: String,
      default: ''
    },
    // 是否上传微信服务器
    sendMedia: {
      type: [Boolean, String],
      default: false
    },
    accept: {
      type: String,
      default: 'image/png,image/gif,image/jpeg,image/webp'
    }
  },
  data() {
    return {
      files: [],
      imageUrl: '',
      action: process.env.VUE_APP_BASE_API + process.env.VUE_APP_UPLOAD_IMG_URL,
      headers: {
        'X-Token': getToken()
      },
      file: this.img,
      name: 'file'
    }
  },
  methods: {
    addFile() {
      this.$refs.editupload.$el.querySelector('input').click()
    },
    inputError(err) {
      this.$toast.error(err || '上传失败')
      this.file = false
      this.$refs.editupload.clear() // 清空文件列表
    },
    /**
     * 添加，更新，移除后
     * @param  newFile   只读
     * @param  oldFile   只读
     * @return undefined
     */
    inputFile: function(newFile, oldFile) {
      if (newFile && this.$refs.editupload.active) {
        // 上传错误
        if (newFile.error !== oldFile.error) {
          this.inputError()
        }

        // 上传成功
        if (newFile.success !== oldFile.success) {
          if (newFile.response.code !== 200) {
            this.inputError(newFile.response.message)
          } else {
            this.$toast.success('上传成功')
            this.$emit('after-upload', newFile.response.data)
          }
        }
      }
      // 自动上传
      if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
        if (!this.$refs.editupload.active) {
          this.$refs.editupload.active = true
        }
      }
    },
    /**
     * Pretreatment
     * @param  newFile   读写
     * @param  oldFile   只读
     * @param  prevent   阻止回调
     * @return undefined
     */
    inputFilter: function(newFile, oldFile, prevent) {
      if (newFile && !oldFile) {
        // 添加文件
        // 过滤不是图片后缀的文件
        if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
          this.$toast.error('不支持的文件类型')
          return prevent()
        }
      }

      // 创建 blob 字段 用于图片预览
      newFile.blob = ''
      const URL = window.URL || window.webkitURL
      if (URL && URL.createObjectURL) {
        newFile.blob = URL.createObjectURL(newFile.file)
        this.file = newFile.blob
      }
    }
  }
}
</script>

<style scoped>

</style>
