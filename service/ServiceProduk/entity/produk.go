package entity

import "time"

type Produk struct {
	ID          uint64    `gorm:"primary_key:auto_increment"`
	Name        string    `gorm:"type:varchar(255)" json:"name"`
	Weight      string    `gorm:"type:varchar(255)" json:"weight"`
	Description string    `gorm:"type:text" json:"description"`
	Stock       string    `gorm:"type:varchar(255)" json:"stock"`
	Photo       string    `gorm:"type:varchar(255)" json:"photo"`
	Price       string    `gorm:"type:varchar(255)" json:"price"`
	Created_at  time.Time `gorm:"type:datetime" json:"created_at"`
	Updated_at  time.Time `gorm:"type:datetime" json:"updated_at"`
	UserID      uint64    `gorm:"not null" json:"user_id"`
}
