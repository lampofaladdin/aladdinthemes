# AladdinThemes 

## Project setup
```
npm install
```

### 监听sass文件
```
npm run dev
```

### 压缩主题目录

npm run zip

### 压缩wordpress目录（不包括wp-config.php,.htacess,.git等)
```
npm run zip:all
```

### 压缩主题目录，添加css兼容性，并压缩css
```
npm run build 
```

### 压缩wordpress目录，添加css兼容性，并压缩css
```
npm run build:all 
```
## 注意事项
```
1、压缩命令通过好压实现，需要安装好压(链接：http://haozip.2345.cc/)
```

## 目录说明
```

assets                  静态资源目录
  |----sass             sass文件
  |----js               js文件
  |----images           图片
lib                     公共库
view                    模板目录
  |----component        组件公共库
404.php                 404页面
footer.php              脚部公共内容
header.php              头部公共内容
functions.php           自定义方法类
index.php               入口文件
page.php                page类分发模板
sidebar.php             侧边栏
single.php              单独页面
style.scss              样式内容
screenshot.png          主题图片
readme.md               说明文件
package.json            package.json

