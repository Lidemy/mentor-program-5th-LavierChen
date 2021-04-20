## 交作業流程

1. 將 Github 課綱及內容下載到 host 上

```
git clone https://github.com/Lidemy/mentor-program-5th-LavierChen.git
```

2. 寫作業的前置動作：開啟一個新的 branch

   `git branch week1`

3. 切換 branch 至 week1，開始寫本週作業

   `git checkout week1`

4. 寫完作業後，將所有檔案加入版本控制

   `git add .`

5. 建立版本控制

   `git commit -m "week1 finished"`

6. 將檔案同步到遠端的 GitHub repository

   `git push origin week1`

7. 在自己的 Github repository 頁面上 `New pull request`
8. 複製 pull request 連結，至 Lidemy 簡易學習系統繳交作業

---

### 助教批改完成

1. 助教點選 `Merge pull request` 後，Github 上的 branch week1 已經合併回 master
2. 回到 host，將 branch 切回 master

   `git checkout master`

3. 將遠端 Github 上修改過的 master 同步到 host

   `git pull origin master`

4. 刪除 host 上已合併回 master 的 branch

   `git branch -d week1`
