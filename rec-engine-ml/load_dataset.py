
import os
os.environ["CUDA_VISIBLE_DEVICES"] = ""   # disables GPU
os.environ["FORCE_CPU"] = "1"


import torch
import torch.nn as nn
import torch.nn.functional as F
import torch.optim as optim
import json


file_path = "ml_dataset.json"

with open(file_path, "r") as f:
    data = json.load(f)

for entry in data[:3]:
    print(entry)


action_mapping = {"view": 1, "click": 2, "purchase": 5}
categories = list(set(entry['product_category'] for entry in data))
category_mapping = {cat: idx for idx, cat in enumerate(categories)}
domains = list(set(entry['user_email_domain'] for entry in data))
domain_mapping = {dom: idx for idx, dom in enumerate(domains)}

for entry in data:
    entry['interaction_action_num'] = action_mapping.get(entry['interaction_action'], 0)
    entry['product_category_num'] = category_mapping.get(entry['product_category'], -1)
    entry['user_email_domain_num'] = domain_mapping.get(entry['user_email_domain'], -1)

for entry in data[:3]:
    print({
        "user_id": entry['user_id'],
        "product_id": entry['product_id'],
        "interaction_weight": entry['interaction_weight'],
        "interaction_action_num": entry['interaction_action_num'],
        "product_category_num": entry['product_category_num'],
        "user_email_domain_num": entry['user_email_domain_num'],
        "product_price": entry['product_price']
    })

features = []
targets = []

for entry in data:
    feature = [
        entry['user_id'],
        entry['product_id'],
        entry['interaction_action_num'],
        entry['product_category_num'],
        entry['user_email_domain_num'],
        entry['product_price']
    ]
    features.append(feature)
    targets.append(entry['interaction_weight'])

# Convert to tensors
X = torch.tensor(features, dtype=torch.float32)
y = torch.tensor(targets, dtype=torch.float32).unsqueeze(1)  # column vector

print("Features shape:", X.shape)
print("Targets shape:", y.shape)


class RecommendationModel(nn.Module):
    def __init__(self, input_size):
        super(RecommendationModel, self).__init__()
        self.fc1 = nn.Linear(input_size, 16)
        self.fc2 = nn.Linear(16, 8)
        self.fc3 = nn.Linear(8, 1)

    def forward(self, x):
        x = F.relu(self.fc1(x))
        x = F.relu(self.fc2(x))
        x = self.fc3(x)
        return x


input_size = X.shape[1]
model = RecommendationModel(input_size)
print(model)


criterion = nn.MSELoss()
optimizer = optim.Adam(model.parameters(), lr=0.01)
epochs = 100


for epoch in range(epochs):
    optimizer.zero_grad()
    outputs = model(X)
    loss = criterion(outputs, y)
    loss.backward()
    optimizer.step()
    if (epoch+1) % 10 == 0:
        print(f'Epoch [{epoch+1}/{epochs}], Loss: {loss.item():.4f}')
