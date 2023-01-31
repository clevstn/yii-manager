###### 查看控制台命令
> `yii help`

#### 一、db迁移命令
+ yii-manager
`yii migrate/* --migrationPath=@app/builder/database/migrations`
+ 当前应用
`yii migrate/* --migrationPath=@app/migrations/db`

#### 二、db1迁移命令
+ yii-manager
`无`
+ 当前应用
`yii migrate/* --migrationPath=@app/migrations/db1 --db=db1`

#### 三、tree生成
`tree -L 3 -I ".gitignore|.idea|vendor|runtime"> dirtree.md`




