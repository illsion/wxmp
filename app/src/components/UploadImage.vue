<template>
  <span>
    <file-upload
      ref="upload"
      v-model="files"
      :post-action="action"
      :accept="accept"
      :headers="headers"
      @input-file="inputFile"
      @input-filter="inputFilter"
    >
      <img
        v-if="file !== false"
        :src="file"
        :style="{
          height: iconSize + 'px',
          width: iconSize + 'px'
        }"
      >
      <mu-icon v-else value="wallpaper" color="info" small class="upload-button" :size="iconSize" />
      <div class="caption">
        添加图片
      </div>
    </file-upload>
  </span>
</template>

<script>
import VueUploadComponent from 'vue-upload-component'
import { getToken } from '../utils/cookie'

export default {
  name: 'Upload',
  components: {
    FileUpload: VueUploadComponent
  },
  props: {
    iconSize: {
      type: Number,
      default: 80
    },
    img: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      files: [],
      imageUrl: '',
      action: process.env.VUE_APP_BASE_API + process.env.VUE_APP_UPLOAD_IMG_URL,
      accept: 'image/png,image/gif,image/jpeg,image/webp',
      headers: {
        'X-Token': getToken()
      },
      file: false
    }
  },
  watch: {
    img(newVal) {
      if (!(newVal === null || newVal === false || newVal === '')) {
        this.file = newVal
      } else {
        this.file = false
      }
    }
  },
  methods: {
    emitInput(val) {
      this.$emit('input', val)
    },
    /**
     * 添加，更新，移除后
     * @param  newFile   只读
     * @param  oldFile   只读
     * @return undefined
     */
    inputFile: function(newFile, oldFile) {
      if (newFile && this.$refs.upload.active) {
        // 上传错误
        if (newFile.error !== oldFile.error) {
          this.$toast.error('上传失败')
          this.file = false
          this.$refs.upload.clear() // 清空文件列表
        }

        // 上传成功
        if (newFile.success !== oldFile.success) {
          this.$toast.success('上传成功')
          this.emitInput(newFile.response.data.path)
        }
      }
      // 自动上传
      if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
        if (!this.$refs.upload.active) {
          this.$refs.upload.active = true
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
  .upload-button {
    margin: 8px;
    margin-bottom: 0;
    vertical-align: middle;
  }
  .caption {
    font-size: 14px;
    font-weight: 400;
  }
</style>
