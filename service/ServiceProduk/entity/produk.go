package entity

import "time"

type Produk struct {
	ID          uint64    `gorm:"primary_key:auto_increment" json:"id"`
	Name        string    `gorm:"type:varchar(255)" json:"name"`
	Weight      string    `gorm:"type:varchar(255)" json:"weight"`
	Description string    `gorm:"type:text" json:"description"`
	Stock       string    `gorm:"type:varchar(255)" json:"stock"`
	Photo       string    `gorm:"type:varchar(255)" json:"photo"`
	Price       string    `gorm:"type:varchar(255)" json:"price"`
	Created_at  time.Time `gorm:"type:timestamp; column:created_at; default: NOW();" json:"created_at"`
	Updated_at  time.Time `gorm:"type:timestamp; column:updated_at; default: NOW();" json:"updated_at"`
	UserID      uint64    `gorm:"not null" json:"user_id"`
}
