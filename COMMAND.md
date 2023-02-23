###### 查看控制台所有命令
> `yii help`

#### 一、db迁移命令
+ yii-manager
`yii migrate/* --migrationPath=@app/builder/database/migrations`
  
  如：数据迁移命令`yii migrate --migrationPath=@app/builder/database/migrations`
  
+ 当前应用
`yii migrate/* --migrationPath=@app/migrations/db`

  如：数据迁移命令`yii migrate --migrationPath=@app/migrations/db`

#### 二、db1迁移命令
+ yii-manager
`无`
+ 当前应用
`yii migrate/* --migrationPath=@app/migrations/db1 --db=db1`

#### 三、tree生成
`tree -L 3 -I ".gitignore|.idea|vendor|runtime"> dirtree.md`

#### 四、清除服务端缓存命令
> `yii clear/asset` 清除已发布的静态文件（JS、CSS等）

> `yii clear/cache` 清除`runtime`下`cache`缓存

> `yii clear/runtime` 清除`runtime`下所有目录






