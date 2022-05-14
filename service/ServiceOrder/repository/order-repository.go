package repository

import (
	"github.com/rickyananda1/golang_gin_gorm_JWT/entity"
	"gorm.io/gorm"
)

type OrderRepository interface {
	InsertOrder(b entity.Order) entity.Order
	UpdateOrder(b entity.Order) entity.Order
	DeleteOrder(b entity.Order)
	AllOrder() []entity.Order
	FindOrderID(bookID uint64) entity.Order
}

type orderConnection struct {
	connection *gorm.DB
}

func NewOrderRepository(dbConn *gorm.DB) OrderRepository {
	return &orderConnection{
		connection: dbConn,
	}
}

func (db *orderConnection) InsertOrder(b entity.Order) entity.Order {
	db.connection.Save(&b)
	db.connection.Preload("Order").Find(&b)
	return b
}

func (db *orderConnection) UpdateOrder(b entity.Order) entity.Order {
	db.connection.Save(&b)
	db.connection.Preload("Order").Find(&b)
	return b
}

func (db *orderConnection) DeleteOrder(b entity.Order) {
	db.connection.Delete(&b)
}

func (db *orderConnection) FindOrderID(orderID uint64) entity.Order {
	var order entity.Order
	db.connection.Preload("Order").Find(&order, orderID)
	return order
}

func (db *orderConnection) AllOrder() []entity.Order {
	var orders []entity.Order
	db.connection.Preload("Order").Find(&orders)
	return orders
}
