package dto

type ProdukUpdateDTO struct {
	ID          uint64 `json:"id" binding:"required"`
	Name        string `json:"name" form:"name" binding:"required"`
	Weight      string `json:"weight" form:"weight" binding:"required"`
	Description string `json:"description" form:"description" binding:"required"`
	Stock       string `json:"stock" form:"stock" binding:"required"`
	Photo       string `json:"photo" form:"photo" binding:"required"`
	Price       string `json:"price" form:"price" binding:"required"`
	UserID      uint64 `json:"user_id,omitempty" form:"user_id, omitempty"`
}

type ProdukCreateDTO struct {
	Name        string `json:"name" form:"name" binding:"required"`
	Weight      string `json:"weight" form:"weight" binding:"required"`
	Description string `json:"description" form:"description" binding:"required"`
	Stock       string `json:"stock" form:"stock" binding:"required"`
	Photo       string `json:"photo" form:"photo" binding:"required"`
	Price       string `json:"price" form:"price" binding:"required"`
	UserID      uint64 `json:"user_id,omitempty" form:"user_id, omitempty"`
}
