<!-- https://stackoverflow.blog/2021/07/05/best-practices-for-writing-code-comments/ -->

1. [გადმოწერა](https://github.com/DavidTbilisi/larauth/archive/refs/heads/master.zip) და შესაბამის ადგილზე (./www) ამოარქივება
2. სახელის გადარქმევა. მაგალითად: `larauth` > `catalogue`
3. პროექტის (`catalogue`) საქაღალდეში შესვლა
4. `.env.example` -ის დაკოპირება და ახალი ფაილის შექმნა სახელად `.env` რომელშიც მონაცემთა ბაზის პარამეტრები იქნება გასაწერი.
5. ამ საქაღალდეში `CMD` -ს გახსნა
6. `CMD` -ში თითო თითო ბრძანების აკრეფვა



იმისთვის რომ ლარაველისთვის საჭირო კომპონენტები გადმოიწეროს 
```bash
composer install
```

იმისთვის რომ უსაფრთხოებისთვის საჭირო გასაღები დააგენერიროს
```bash
php artisan key:generate
```

მონაცემთა ბაზის შესაქმნელად და Default-ები დააგენერიროს
```bash
php artisan migrate --seed
```

Default ადმინისტრატორი
```bash
admin@archive.gov.ge
```
პაროლი
```bash
123456789
```
---

---
## Screenshots


Integrated Image viewer  
![ElFinder](Screenshots/Screenshot_20220614_104411.png)


Integrated ElFinider  
![ElFinder](Screenshots/Screenshot_20220614_104517.png)


Information Object Permissions  
![Information Object Permissions](Screenshots/Screenshot_20220614_104550.png)


Information Object  
![Information Object](Screenshots/Screenshot_20220614_104238.png)


Main view  
![Main View](Screenshots/Screenshot_20220614_104135.png)










