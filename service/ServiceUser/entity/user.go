package entity

import "time"

type User struct {
	ID         uint64    `gorm:"primary_key:auto_increament" json:"id"`
	Name       string    `gorm:"type:varchar(255)" json:"name"`
	Email      string    `gorm:"uniqueIndex;type:varchar(255)" json:"email"`
	Password   string    `gorm:"->;<-;not null" json:"-"`
	Role       string    `gorm:"type:varchar(255)" json:"role"`
	Phone      string    `gorm:"type:varchar(255)" json:"phone"`
	Created_at time.Time `gorm:"type:datetime" json:"created_at"`
	Updated_at time.Time `gorm:"type:datetime" json:"updated_at"`
	Token      string    `gorm:"-" json:"token,omitempty"`
}
