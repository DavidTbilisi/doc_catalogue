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
dchincharashvili@archive.gov.ge
```
პაროლი
```bash
123456789
```
---

## Todo
- [x] მომხმარებლები  
- [x] ადმინისტრირების ჯგუფები  
- [x] პრივილეგიები  
- [x] ობიექტების ტიპები  


- [x] http://localhost:8000/admin/types/add - parent ველი წავშალოთ

- [] http://localhost:8000/admin/types/show/fonds - + ნიშანი ველების დასამატებლად უნდა იყოს. და ველების წაშლაც უნდა იყოს შესაძლებელი

- [x] http://localhost:8000/admin/types/add - ღილაკების ფერები შევცვალოთ

- [x] http://localhost:8000/admin/types/add - არ მუშაობს

ობიექტის რედაქტირება არ მუშაობს
